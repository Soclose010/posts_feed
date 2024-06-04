<?php

namespace App\Services\User;

use App\DataTransferObjects\UserDto;
use App\Enums\Action;
use App\Enums\FieldName;
use App\Enums\UserRole;
use App\Events\Log\User\LogActionEvent;
use App\Exceptions\ExistedEmailException;
use App\Models\User;
use App\Traits\FilterFieldsTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class UserService
{
    use FilterFieldsTrait;

    public function create(UserDto $dto): UserDto
    {
        $user = User::create($this->filteredFields($dto));
        LogActionEvent::dispatch(null, $user->toJson(), Action::Create);
        return UserDto::fromModel($user);
    }

    /**
     * @throws NotFound
     * @throws ExistedEmailException
     */
    public function update(UserDto $dto): UserDto
    {
        $user = $this->getUser($dto->id);
        $oldUser = $user->toJson();
        Gate::authorize("edit", $user->id);
        try {
            $user = tap($user->fill($this->filteredFields($dto)))->save();
        } catch (UniqueConstraintViolationException) {
            throw new ExistedEmailException();
        }
        LogActionEvent::dispatch($oldUser, $user->toJson(), Action::Update);
        return UserDto::fromModel($user);
    }

    /**
     * @throws NotFound
     */
    public function delete(string $id): void
    {
        Gate::authorize("edit", $id);
        $user = $this->getUser($id);
        $oldUser = $user->toJson();
        $user->delete();
        LogActionEvent::dispatch($oldUser,$user->toJson(), Action::Delete);
    }

    /**
     * @throws NotFound
     */
    public function get(string $value, FieldName $field = FieldName::Id): UserDto
    {
        return UserDto::fromModel($this->getUser($value, $field));
    }

    /**
     * @throws NotFound
     */
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
