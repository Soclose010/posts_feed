<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;
use App\Models\User;

class UserService
{
    public function create(UserDto $dto): UserDto
    {
        $attributes = array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
        $user = User::create($attributes);
        return UserDto::fromModel($user);
    }

    public function update(UserDto $dto): UserDto
    {
        $user = $this->getUser($dto->id);
        $attributes = array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
        $user = tap($user->fill($attributes))->save();
        return UserDto::fromModel($user);
    }

    public function delete(string $id): void
    {
        User::where('id', $id)->delete();
    }

    public function get(string $id): UserDto
    {
        $user = User::where("id", $id)->first();
        return UserDto::fromModel($user);
    }

    public function getUser(string $id): User
    {
        return User::find($id);
    }
}
