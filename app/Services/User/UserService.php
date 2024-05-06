<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;

class UserService
{
    public function create(UserDto $dto): UserDto
    {
        return $dto;
    }

    public function update(UserDto $dto): void
    {

    }

    public function delete(string $id): void
    {

    }

}
