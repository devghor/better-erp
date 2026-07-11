<?php

namespace App\Services\Uam;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): LengthAwarePaginator;

    public function getUserById(string $id): User;

    public function createUser(array $data): User;

    public function updateUser(User $user, array $data): User;

    public function deleteUser(User $user): bool;
}
