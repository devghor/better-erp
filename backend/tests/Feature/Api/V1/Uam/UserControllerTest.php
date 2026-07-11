<?php

namespace Tests\Feature\Api\V1\Uam;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_requests_are_rejected(): void
    {
        $user = User::factory()->create();

        $this->getJson('/api/v1/uam/users')->assertStatus(401);
        $this->postJson('/api/v1/uam/users', [])->assertStatus(401);
        $this->getJson("/api/v1/uam/users/{$user->id}")->assertStatus(401);
        $this->putJson("/api/v1/uam/users/{$user->id}", [])->assertStatus(401);
        $this->deleteJson("/api/v1/uam/users/{$user->id}")->assertStatus(401);
    }

    public function test_index_returns_paginated_users_newest_first(): void
    {
        $actingUser = User::factory()->create();
        $older = User::factory()->create(['created_at' => now()->subDay()]);
        $newer = User::factory()->create(['created_at' => now()]);

        $response = $this->actingAs($actingUser)->getJson('/api/v1/uam/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'per_page', 'total'],
            ]);

        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->search($newer->id) < $ids->search($older->id));
    }

    public function test_show_returns_user_without_sensitive_fields(): void
    {
        $actingUser = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($actingUser)->getJson("/api/v1/uam/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token');
    }

    public function test_show_returns_404_for_missing_user(): void
    {
        $actingUser = User::factory()->create();

        $this->actingAs($actingUser)
            ->getJson('/api/v1/uam/users/999999')
            ->assertStatus(404);
    }

    public function test_store_creates_user(): void
    {
        $actingUser = User::factory()->create();

        $response = $this->actingAs($actingUser)->postJson('/api/v1/uam/users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.email', 'jane@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
        ]);
    }

    public function test_store_requires_name_email_and_password(): void
    {
        $actingUser = User::factory()->create();

        $this->actingAs($actingUser)
            ->postJson('/api/v1/uam/users', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_store_rejects_duplicate_email(): void
    {
        $actingUser = User::factory()->create();
        $existing = User::factory()->create();

        $this->actingAs($actingUser)
            ->postJson('/api/v1/uam/users', [
                'name' => 'Jane Doe',
                'email' => $existing->email,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_store_rejects_password_confirmation_mismatch(): void
    {
        $actingUser = User::factory()->create();

        $this->actingAs($actingUser)
            ->postJson('/api/v1/uam/users', [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_update_applies_partial_changes(): void
    {
        $actingUser = User::factory()->create();
        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($actingUser)->putJson("/api/v1/uam/users/{$user->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_update_rejects_duplicate_email(): void
    {
        $actingUser = User::factory()->create();
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($actingUser)
            ->putJson("/api/v1/uam/users/{$user->id}", ['email' => $other->email])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_update_allows_keeping_own_email(): void
    {
        $actingUser = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($actingUser)
            ->putJson("/api/v1/uam/users/{$user->id}", [
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->assertStatus(200);
    }

    public function test_update_returns_404_for_missing_user(): void
    {
        $actingUser = User::factory()->create();

        $this->actingAs($actingUser)
            ->putJson('/api/v1/uam/users/999999', ['name' => 'New Name'])
            ->assertStatus(404);
    }

    public function test_destroy_deletes_user(): void
    {
        $actingUser = User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($actingUser)
            ->deleteJson("/api/v1/uam/users/{$user->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_destroy_returns_404_for_missing_user(): void
    {
        $actingUser = User::factory()->create();

        $this->actingAs($actingUser)
            ->deleteJson('/api/v1/uam/users/999999')
            ->assertStatus(404);
    }
}
