<?php

namespace App\DataTransferObjects;

use App\Enums\UserRole;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Carbon\CarbonInterface;

class UserDto
{
    public readonly string $id;
    public readonly string $username;
    public readonly string $email;
    public readonly string $password;
    public readonly UserRole $role;
    public readonly CarbonInterface $created_at;
    public readonly CarbonInterface $updated_at;

    private function __construct()
    {
    }

    public static function fromAuthRequest(AuthRequest $request): self
    {
        $dto = new self();
        $dto->email = $request->validated("email");
        $dto->password = $request->validated("password");
        return $dto;
    }

    public static function fromCreateRequest(UserCreateRequest $request): self
    {
        $dto = new self();
        $dto->username = $request->validated("username");
        $dto->email = $request->validated("email");
        $dto->password = $request->validated("password");
        $dto->role = UserRole::User;
        return $dto;
    }

    public static function fromModel(User $user): self
    {
        $dto = new self();
        $dto->id = $user->id;
        $dto->username = $user->username;
        $dto->email = $user->email;
        $dto->role = $user->role;
        $dto->created_at = $user->created_at;
        return $dto;
    }

    public static function fromUpdateRequest(UserUpdateRequest $request): self
    {
        $dto = new self();
        $dto->id = $request->validated("id");
        $dto->username = $request->validated("username");
        $dto->email = $request->validated("email");
        $dto->password = $request->validated("password");
        return $dto;
    }

}
