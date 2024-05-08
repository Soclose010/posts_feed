<?php

namespace App\Services\Auth;

use App\DataTransferObjects\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(User $user)
    {
        Auth::login($user);
    }
    public function tryLogin(UserDto $dto): void
    {
        $loginData = [
            "email" => $dto->email,
            "password" => $dto->password
        ];
        if (!Auth::attempt($loginData))
        {
            redirect()->back()->withErrors([]);
        }
    }

    public function logout(): void
    {
        Auth::logout();
    }


}
