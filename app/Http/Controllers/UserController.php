<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserDto;
use App\Exceptions\ExistedEmailException;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\Auth\AuthService;
use App\Services\Post\PostService;
use App\Services\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService,
        private readonly PostService $postService
    )
    {
    }

    public function create()
    {
        return view('layouts.users.create');
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $dto = UserDto::fromCreateRequest($request);
        $userDto = $this->userService->create($dto);
        try {
            $user = $this->userService->getUser($userDto->id);
        } catch (NotFound) {
            abort(404);
        }
        $this->authService->login($user);
        return redirect()->route("index");
    }

    public function show(string $id)
    {
        $userDto = $this->userService->get($id);
        $postPaginator = $this->postService->getUserPostsPaginate(5, $id);
        $canEdit = Gate::allows("edit", $id);
        return view('layouts.users.show', compact("userDto", "postPaginator", "canEdit"));
    }

    public function edit(string $id)
    {
        Gate::authorize("edit", $id);
        try {
            $userDto = $this->userService->get($id);
        } catch (NotFound) {
            abort(404);
        }
        return view("layouts.users.edit", compact("userDto"));
    }

    public function update(UserUpdateRequest $request, string $id): RedirectResponse
    {
        $dto = UserDto::fromUpdateRequest($request, $id);
        try {
            $this->userService->update($dto);
        } catch (ExistedEmailException) {
            throw new \DomainException();
        } catch (NotFound) {
            abort(404);
        }
        if ($dto->password !== null) {
            $this->authService->logout();
        }
        return redirect()->route("index");
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->userService->delete($id);
        } catch (NotFound)
        {
            abort(404);
        }
        $this->authService->logout();
        return redirect()->route("index");
    }
}
