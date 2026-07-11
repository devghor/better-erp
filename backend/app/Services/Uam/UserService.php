<?php

namespace App\Services\Uam;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator
    {
        return User::latest()->paginate();
    }

    public function getUserById(string $id): User
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data): User
    {
        return DB::transaction(fn (): User => User::create($data));
    }

    public function updateUser(User $user, array $data): User
    {
        DB::transaction(function () use ($user, $data): void {
            $user->update($data);
        });

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return DB::transaction(fn (): bool => (bool) $user->delete());
    }
}
