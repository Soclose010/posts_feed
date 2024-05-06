<?php

namespace App\DataTransferObjects;

use App\Enums\UserRole;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;

class UserDto
{
    public readonly ?string $id;
    public readonly ?string $username;
    public readonly ?string $email;
    public readonly ?string $password;
    public readonly ?string $oldPassword;
    public readonly ?UserRole $role;
    public readonly ?CarbonInterface $created_at;
    public readonly ?CarbonInterface $updated_at;

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
        $dto->id = Auth::id();
        $dto->username = $request->validated("username");
        $dto->email = $request->validated("email");
        $dto->password = $request->validated("password");
        $dto->oldPassword = $request->validated("old_password");
        return $dto;
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->id = $data["id"] ?? null;
        $dto->username = $data["username"] ?? null;
        $dto->email = $data["email"] ?? null;
        $dto->password = $data["password"] ?? null;
        $dto->oldPassword = $data["old_password"] ?? null;
        $dto->role = $data["role"] ?? null;
        $dto->created_at = $data["created_at"] ?? null;
        $dto->updated_at = $data["updated_at"] ?? null;
        return $dto;
    }
}
