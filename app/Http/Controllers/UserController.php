<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService
    )
    {
    }

    public function create()
    {
        //
    }

    public function store(UserCreateRequest $request): void
    {
        $dto = UserDto::fromCreateRequest($request);
        $userDto = $this->userService->create($dto);
        $this->authService->login($userDto);
        redirect(route("index"));
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UserUpdateRequest $request, string $id): void
    {
        $dto = UserDto::fromUpdateRequest($request, $id);
        $userDto = $this->userService->update($dto);
        redirect(route("users.show",[$userDto->id]));
    }

    public function destroy(string $id): void
    {
        $this->userService->delete($id);
        $this->authService->logout();
        redirect(route("index"));
    }
}
