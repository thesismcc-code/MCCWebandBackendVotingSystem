<?php

namespace App\Domain\Auth;

use App\Domain\User\User;

interface AuthRepository
{
    public function login(string $email, string $password): ?User;
    public function logout(string $user_id): bool;
    public function loginJwt(string $email, string $password): ?string; // ← returns token
    public function logoutJwt(string $token): bool;
}
