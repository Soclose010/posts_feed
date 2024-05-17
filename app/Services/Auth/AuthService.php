<?php

namespace App\Services\Auth;

use App\DataTransferObjects\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(User $user): void
    {
        Auth::login($user);
    }

    public function tryLogin(UserDto $dto): bool
    {
        $loginData = [
            "email" => $dto->email,
            "password" => $dto->password
        ];
        return Auth::attempt($loginData);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
