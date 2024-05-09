<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;
use App\Exceptions\ExistedEmailException;
use App\Models\User;
use App\Traits\FilterFieldsTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;

class UserService
{
    use FilterFieldsTrait;
    public function create(UserDto $dto): UserDto
    {
        $user = User::create($this->fieldsToUpdate($dto));
        return UserDto::fromModel($user);
    }

    public function update(UserDto $dto): UserDto
    {
        $user = $this->getUser($dto->id);
        Gate::authorize("edit", $user->id);
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
        Gate::authorize("edit", $id);
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
}
