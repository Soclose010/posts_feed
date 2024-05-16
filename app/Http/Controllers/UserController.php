<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Exceptions\ExistedEmailException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService
    )
    {
    }

    public function create()
    {
        return view('layouts.users.create');
    }

    /**
     * @throws NotFound
     */
    public function store(UserCreateRequest $request): RedirectResponse
    {
        $dto = UserDto::fromCreateRequest($request);
        $userDto = $this->userService->create($dto);
        $user = $this->userService->getUser($userDto->id);
        $this->authService->login($user);
        return redirect()->route("index");
    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view("layouts.users.edit");
    }

    public function update(UserUpdateRequest $request, string $id): RedirectResponse
    {
        $dto = UserDto::fromUpdateRequest($request, $id);
        try {
            $userDto = $this->userService->update($dto);
        }
        catch (ExistedEmailException)
        {
            throw new \DomainException();
        }
        if ($dto->password !== null) {
            $this->authService->logout();
        }
        return redirect()->route("index");
    }

    /**
     * @throws NotFound
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->userService->delete($id);
        $this->authService->logout();
        return redirect()->route("index");
    }
}
