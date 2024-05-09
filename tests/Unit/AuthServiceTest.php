<?php

namespace Tests\Unit;

use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use DatabaseMigrations;
    private AuthService $authService;
    private UserService $userService;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
        $this->authService = new AuthService();
        $this->user = $this->createUser();
    }

    public function test_login()
    {
        $dto = UserDto::fromArray([
            "email" => $this->user->email,
            "password" => "123"
        ]);
        $this->authService->tryLogin($dto);
        $this->assertAuthenticated();
    }

    public function test_logout()
    {
        $this->actingAs($this->user);
        $this->authService->logout();
        $this->assertGuest();
    }

    private function createUser(): Model
    {
        $dto = UserDto::fromArray([
            "username" => "ivan",
            "email" => fake()->email(),
            "password" => "123",
            "role" => UserRole::User,
        ]);

        return $this->userService->getUser($this->userService->create($dto)->id);
    }
}
