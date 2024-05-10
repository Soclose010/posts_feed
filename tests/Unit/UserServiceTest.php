<?php

namespace Tests\Unit;

use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Exceptions\ExistedEmailException;
use App\Services\Action\ActionServiceInterface;
use App\Services\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(ActionServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive("write")->andReturns();
        });
        $this->service = $this->app->make(UserService::class);
    }

    public function test_get_exist_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $createdUserDto = $this->service->create($createDto);
        $userDto = $this->service->get($createdUserDto->id);
        $this->assertEquals($createdUserDto, $userDto);
    }

    public function test_get_non_exist_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $createdUserDto = $this->service->create($createDto);
        $id = Str::uuid();
        $this->expectException(NotFound::class);
        $userDto = $this->service->get($id);
    }

    public function test_user_create()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $resultDto = $this->service->create($createDto);
        $this->assertNotNull($resultDto->id);
        $this->assertEquals($createDto->username, $resultDto->username);
        $this->assertEquals($createDto->email, $resultDto->email);
        $this->assertTrue(Hash::check($createDto->password, $this->service->getUser($resultDto->id)->password));
        $this->assertEquals(UserRole::User, $resultDto->role);
        $this->assertNotNull($resultDto->created_at);
        $this->assertNotNull($resultDto->updated_at);
    }

    public function test_user_create_exists_email()
    {
        $createDto = UserDto::fromArray([
            "username" => "1",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $this->service->create($createDto);
        $createDto = UserDto::fromArray([
            "username" => "2",
            "email" => fake()->email(),
            "password" => "12345",
            "role" => UserRole::User
        ]);
        $resultDto = $this->service->create($createDto);
        $this->assertEquals($createDto->username, $resultDto->username);
        $this->assertEquals($createDto->email, $resultDto->email);
        $this->assertTrue(Hash::check($createDto->password, $this->service->getUser($resultDto->id)->password));
    }

    public function test_user_update()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));
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
        $this->assertTrue(Hash::check($dto->password, $this->service->getUser($updatedUserDto->id)->password));
    }

    public function test_user_update_not_own_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));

        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
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
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::Admin
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));

        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto);

        $dto = UserDto::fromArray([
            "id" => $userDto2->id,
            "username" => "new aboba",
            "email" => fake()->email(),
        ]);
        $updatedUserDto = $this->service->update($dto);
        $this->assertEquals($dto->username, $updatedUserDto->username);
        $this->assertEquals($dto->email, $updatedUserDto->email);
    }

    public function test_user_update_exist_email()
    {
        $createDto1 = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);

        $userDto1 = $this->service->create($createDto1);
        $this->actingAs($this->service->getUser($userDto1->id));
        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
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

    /**
     * @throws NotFound
     * @throws ExistedEmailException
     */
    public function test_update_non_exist_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::Admin
        ]);

        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));
        $id = Str::uuid();
        $dto = UserDto::fromArray([
            "id" => $id,
            "username" => "new aboba",
        ]);
        $this->expectException(NotFound::class);
        $this->service->update($dto);
    }

    /**
     * @throws NotFound
     */
    public function test_user_delete()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));
        $this->service->delete($userDto->id);
        $this->expectException(NotFound::class);
        $this->service->get($userDto->id);
    }

    public function test_delete_non_exist_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::Admin
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));
        $id = Str::uuid();
        $this->expectException(NotFound::class);
        $this->service->delete($id);
    }

    public function test_user_delete_another_user()
    {
        $createDto = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));

        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
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
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::Admin
        ]);
        $userDto = $this->service->create($createDto);
        $this->actingAs($this->service->getUser($userDto->id));

        $createDto2 = UserDto::fromArray([
            "username" => "aboba",
            "email" => fake()->email(),
            "password" => "1234",
            "role" => UserRole::User
        ]);
        $userDto2 = $this->service->create($createDto2);
        $this->service->delete($userDto2->id);
        $this->expectException(NotFound::class);
        $this->service->get($userDto2->id);
    }
}
