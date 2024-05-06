<?php

namespace Tests\Unit;

use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Exceptions\ExistedEmailException;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function test_user_create()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234"
        ]);
        $resultDto = $this->service->create($createDto);
        $this->assertNotNull($resultDto->id);
        $this->assertEquals($createDto->username, $resultDto->username);
        $this->assertEquals($createDto->email, $resultDto->email);
        $this->assertTrue(Hash::check($createDto->password, $resultDto->password));
        $this->assertEquals(UserRole::User, $resultDto->role);
        $this->assertNotNull($resultDto->created_at);
        $this->assertNotNull($resultDto->updated_at);
    }

    public function test_user_create_exists_email()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234"
        ]);
        $resultDto = $this->service->create($createDto);
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234"
        ]);
        $this->expectException(ExistedEmailException::class);
        $resultDto = $this->service->create($createDto);
    }

    public function test_user_update()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234"
        ]);

        $userDto = $this->service->create($createDto);

        $dto = UserDto::fromArray([
            "id" => $userDto->id,
            "username" => "new aboba",
            "email" => "new email",
        ]);
        $this->service->update($dto);
        $updatedUserDto = $this->service->get($userDto->id);
        $this->assertEquals($dto->username, $updatedUserDto->username);
        $this->assertEquals($dto->email, $updatedUserDto->email);
        $this->assertNotEquals($userDto->updated_at, $updatedUserDto->updated_at);

        $dto = UserDto::fromArray([
            "id" => $userDto->id,
            "old_password" => "1234",
            "password" => "333"
        ]);
        $this->service->update($dto);
        $updatedUserDto = $this->service->get($userDto->id);
        $this->assertTrue(Hash::check($dto->password, $updatedUserDto->password));
    }

    public function test_user_delete()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234"
        ]);
        $userDto = $this->service->create($createDto);
        $this->service->delete($userDto->id);
        $userDto = $this->service->get($userDto->id);
        $this->assertNull($userDto);
    }
}
