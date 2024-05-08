<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{

    public function __construct(private readonly AuthService $service)
    {
    }

    public function login(AuthRequest $request): void
    {
        $dto = UserDto::fromAuthRequest($request);
        $this->service->tryLogin($dto);
        redirect(route("index"));
    }

    public function logout(): void
    {
        $this->service->logout();
        redirect(route("index"));
    }
}
