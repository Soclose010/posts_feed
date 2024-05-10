<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;
use App\Enums\Action;
use App\Enums\FieldName;
use App\Enums\UserRole;
use App\Exceptions\ExistedEmailException;
use App\Models\User;
use App\Services\Action\ActionServiceInterface;
use App\Traits\FilterFieldsTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class UserService
{
    use FilterFieldsTrait;

    public function __construct(private readonly ActionServiceInterface $actionService)
    {
    }

    public function create(UserDto $dto): UserDto
    {
        $user = User::create($this->fieldsToUpdate($dto));
        $this->actionService::write(
            Auth::id() ?? null,
            $user->id,
            Action::Create,
            null,
            $user
        );
        return UserDto::fromModel($user);
    }

    /**
     * @throws NotFound
     * @throws ExistedEmailException
     */
    public function update(UserDto $dto): UserDto
    {
        $user = $oldUser = $this->getUser($dto->id);
        Gate::authorize("edit", $user->id);
        try {
            $user = tap($user->fill($this->fieldsToUpdate($dto)))->save();
        } catch (UniqueConstraintViolationException) {
            throw new ExistedEmailException();
        }
        $this->actionService::write(
            Auth::id(),
            $user->id,
            Action::Update,
            $oldUser,
            $user
        );
        return UserDto::fromModel($user);
    }

    /**
     * @throws NotFound
     */
    public function delete(string $id): void
    {
        Gate::authorize("edit", $id);
        $user = $this->getUser($id);
        $user->delete();
        $this->actionService::write(
            Auth::id(),
            $user->id,
            Action::Delete,
            $user,
            null
        );
    }

    /**
     * @throws NotFound
     */
    public function get(string $value, FieldName $field = FieldName::Id): UserDto
    {
        return UserDto::fromModel($this->getUser($value, $field));
    }

    public function upgrade(string $id): void
    {
        $user = $this->getUser($id);
        $user->role = UserRole::Admin;
        $user->save();
    }

    /**
     * @throws NotFound
     */
    public function getUser(string $value, FieldName $field = FieldName::Id): User
    {
        if (!$user = User::where($field->value, $value)->first()) {
            throw new NotFound();
        }
        return $user;
    }
}
