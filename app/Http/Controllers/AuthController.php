<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{

    public function __construct(private readonly AuthService $service)
    {
    }

    public function login(AuthRequest $request): RedirectResponse
    {
        $dto = UserDto::fromAuthRequest($request);
        if ($this->service->tryLogin($dto))
        {
            $request->session()->regenerate();
            return redirect()->route("index");
        }
        else
        {
            return redirect()->back()->withInput()->withErrors([
                "login" => 1
            ]);
        }
    }

    public function logout(): RedirectResponse
    {
        $this->service->logout();
        return redirect()->route("index");
    }
}
