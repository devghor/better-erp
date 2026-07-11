<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->issueTokens($user);
    }

    public function refresh(User $user): array
    {
        return DB::transaction(function () use ($user): array {
            $user->currentAccessToken()->delete();

            return $this->issueTokens($user);
        });
    }

    public function authUser(User $user): array
    {
        return $user->toArray();
    }

    private function issueTokens(User $user): array
    {
        $accessTokenTtl = (int) config('sanctum.access_token_ttl');
        $refreshTokenTtl = (int) config('sanctum.refresh_token_ttl');

        return DB::transaction(function () use ($user, $accessTokenTtl, $refreshTokenTtl): array {
            $accessToken = $user->createToken(
                'access_token',
                ['access-api'],
                now()->addMinutes($accessTokenTtl)
            );

            $refreshToken = $user->createToken(
                'refresh_token',
                ['issue-access-token'],
                now()->addMinutes($refreshTokenTtl)
            );

            return [
                'token_type' => 'Bearer',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'expires_in' => $accessTokenTtl * 60,
            ];
        });
    }
}
