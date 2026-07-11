<?php

namespace App\Services\Auth;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(string $email, string $password): array;

    public function refresh(User $user): array;

    public function authUser(User $user): array;
}
