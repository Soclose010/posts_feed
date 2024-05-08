<?php

namespace App\Services\CrudServices\User;

use App\DataTransferObjects\Dto;
use App\DataTransferObjects\UserDto;
use App\Exceptions\ExistedEmailException;
use App\Models\User;
use App\Services\Action\ActionService;
use App\Services\CrudServices\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserService extends CrudService
{

    public function __construct()
    {
        parent::__construct(User::class, UserDto::class);
    }

    /**
     * @throws ExistedEmailException
     */
    public function update(Dto $dto): Dto
    {
        $user = $this->getModel($dto->id);
        $oldUser = clone $user;
        Gate::authorize("edit", $dto->id);
        try {
            $user = tap($user->fill($this->fieldsForOperation($dto)))->save();
        }
        catch (UniqueConstraintViolationException)
        {
            throw new ExistedEmailException();
        }
        ActionService::write(
            Auth::id(),
            $this->getOwner($user),
            "update",
            $oldUser,
            $user
        );
        return $this->dto::fromModel($user);
    }

    public function delete(string $id): void
    {
        Gate::authorize("edit", $id);
        $user = $this->getModel($id);
        $user->delete();
        ActionService::write(
            Auth::id(),
            $this->getOwner($user),
            "delete",
            $user,
            null
        );
    }

    public function getOwner(Model $model): string
    {
        return $model->id;
    }
}
