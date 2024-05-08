<?php

namespace Tests\Unit;

use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Exceptions\ExistedEmailException;
use App\Services\CrudServices\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
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
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $resultDto = $this->service->create($createDto);
        $this->assertNotNull($resultDto->id);
        $this->assertEquals($createDto->username, $resultDto->username);
        $this->assertEquals($createDto->email, $resultDto->email);
        $this->assertTrue(Hash::check($createDto->password, $this->service->getModel($resultDto->id)->password));
        $this->assertEquals(UserRole::User, $resultDto->role);
        $this->assertNotNull($resultDto->created_at);
        $this->assertNotNull($resultDto->updated_at);
    }

    public function test_user_create_exists_email()
    {

        $createDto = UserDto::fromArray([
            "username" => "1",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $this->service->create($createDto);
        $createDto = UserDto::fromArray([
            "username" => "2",
            "email" => "aboba2@abobus.com",
            "password" => "12345",
            "role" => UserRole::User
        ]);
        $resultDto = $this->service->create($createDto);
        $this->assertEquals($createDto->username, $resultDto->username);
        $this->assertEquals($createDto->email, $resultDto->email);
        $this->assertTrue(Hash::check($createDto->password, $this->service->getModel($resultDto->id)->password));
    }

    public function test_user_update()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));
        $dto = UserDto::fromArray([
            "id" => $userDto->id,
            "username" => "new aboba",
            "email" => "new email",
        ]);
        $updatedUserDto = $this->service->update($dto);
        $this->assertEquals($dto->username, $updatedUserDto->username);
        $this->assertEquals($dto->email, $updatedUserDto->email);

        $dto = UserDto::fromArray([
            "id" => $userDto->id,
            "old_password" => "1234",
            "password" => "333"
        ]);
        $updatedUserDto = $this->service->update($dto);
        $this->assertTrue(Hash::check($dto->password, $this->service->getModel($updatedUserDto->id)->password));
    }

    public function test_user_update_not_own_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));

        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba2@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto);

        $dto = UserDto::fromArray([
            "id" => $userDto2->id,
            "username" => "new aboba",
            "email" => "new email",
        ]);
        $this->expectException(AuthorizationException::class);
        $this->service->update($dto);
    }

    public function test_admin_update_another_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::Admin
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));

        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba2@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto);

        $dto = UserDto::fromArray([
            "id" => $userDto2->id,
            "username" => "new aboba",
            "email" => "new email",
        ]);
        $updatedUserDto = $this->service->update($dto);
        $this->assertEquals($dto->username, $updatedUserDto->username);
        $this->assertEquals($dto->email, $updatedUserDto->email);
    }

    public function test_user_update_exist_email()
    {
        $createDto1 = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto1 = $this->service->create($createDto1);
        $this->actingAs($this->service->getModel($userDto1->id));
        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba2@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto2 = $this->service->create($createDto2);

        $dto = UserDto::fromArray([
            "id" => $userDto1->id,
            "email" => $userDto2->email
        ]);
        $this->expectException(ExistedEmailException::class);
        $this->service->update($dto);
    }

    public function test_user_delete()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));
        $this->service->delete($userDto->id);
        $userDto = $this->service->get($userDto->id);
        $this->assertNull($userDto);
    }

    public function test_user_delete_another_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));

        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba2@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto2);
        $this->expectException(AuthorizationException::class);
        $this->service->delete($userDto2->id);
    }

    public function test_admin_delete_another_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba@abobus.com",
            "password" => "1234",
            "role" => UserRole::Admin
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getModel($userDto->id));

        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => "aboba2@abobus.com",
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto2);
        $this->service->delete($userDto2->id);
        $userDto = $this->service->get($userDto2->id);
        $this->assertNull($userDto);
    }
}
