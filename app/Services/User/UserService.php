<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;
use App\Exceptions\ExistedEmailException;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

class UserService
{
    public function create(UserDto $dto): UserDto
    {
        $user = User::create($this->fieldsToUpdate($dto));
        return UserDto::fromModel($user);
    }

    public function update(UserDto $dto): UserDto
    {
        $user = $this->getUser($dto->id);
        try {
            $user = tap($user->fill($this->fieldsToUpdate($dto)))->save();
        }
        catch (UniqueConstraintViolationException)
        {
            throw new ExistedEmailException();
        }
        return UserDto::fromModel($user);
    }

    public function delete(string $id): void
    {
        User::where('id', $id)->delete();
    }

    public function get(string $id): ?UserDto
    {
        if ($user = User::where("id", $id)->first()) {
            return UserDto::fromModel($user);
        }
        return null;
    }

    public function getUser(string $id): ?User
    {
        return User::find($id);
    }

    private function fieldsToUpdate(UserDto $dto): array
    {
        return array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
    }
}
