<?php

namespace App\DataTransferObjects;

use App\Enums\UserRole;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UserDto extends Dto
{
    public readonly ?string $id;
    public readonly ?string $username;
    public readonly ?string $email;
    public readonly ?string $password;
    public readonly ?UserRole $role;
    public readonly ?CarbonInterface $created_at;
    public readonly ?CarbonInterface $updated_at;

    private function __construct()
    {
    }

    public static function fromAuthRequest(AuthRequest $request): static
    {
        return self::fromArray($request->validated());
    }

    public static function fromCreateRequest(UserCreateRequest $request): static
    {
        return self::fromArray([$request->validated(), "role" => UserRole::User]);
    }

    public static function fromModel(Model $model): static
    {
        if (!$model instanceof User) {
            throw new InvalidArgumentException();
        }
        $dto = new static();
        $dto->id = $model->id;
        $dto->username = $model->username;
        $dto->email = $model->email;
        $dto->role = $model->role;
        $dto->created_at = $model->created_at;
        $dto->updated_at = $model->updated_at;
        return $dto;
    }

    public static function fromUpdateRequest(UserUpdateRequest $request, string $id): static
    {
        return self::fromArray([$request->validated(), "id" => $id]);
    }

    public static function fromArray(array $data): static
    {
        $dto = new static();
        $dto->id = $data["id"] ?? null;
        $dto->username = $data["username"] ?? null;
        $dto->email = $data["email"] ?? null;
        $dto->password = $data["password"] ?? null;
        $dto->role = $data["role"] ?? null;
        $dto->created_at = $data["created_at"] ?? null;
        $dto->updated_at = $data["updated_at"] ?? null;
        return $dto;
    }
}
