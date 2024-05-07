<?php

namespace Tests\Unit;

use App\DataTransferObjects\PostDto;
use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Post\PostService;
use App\Services\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use DatabaseMigrations;

    private PostService $postService;
    private UserService $userService;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->user = $this->createUser();
        $this->admin = $this->createAdmin();
    }

    public function test_create_post()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $postDto = $this->postService->create($dto);
        $this->assertNotNull($postDto->id);
        $this->assertEquals($dto->title, $postDto->title);
        $this->assertEquals($dto->body, $postDto->body);
        $this->assertEquals($dto->user_id, $postDto->user_id);
        $this->assertNotNull($postDto->created_at);
        $this->assertNotNull($postDto->updated_at);
    }

    public function test_update_post()
    {
        $this->actingAs($this->user);
        $dto1 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $postDto = $this->postService->create($dto1);
        $dto2 = PostDto::fromArray([
            "id" => $postDto->id,
            "title" => "aboba title 2",
            "body" => "body abobus 2",
            "user_id" => $this->user->id,
        ]);
        $postDto2 = $this->postService->update($dto2);
        $this->assertEquals($dto2->title, $postDto2->title);
        $this->assertEquals($dto2->body, $postDto2->body);
    }

    public function test_update_not_own_post_by_user()
    {
        $this->actingAs($this->user);
        $dto1 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto1);

        $this->actingAs($this->admin);
        $dto2 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $postDto2 = $this->postService->create($dto2);

        $this->actingAs($this->user);
        $dto3 = PostDto::fromArray([
            "id" => $postDto2->id,
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $postDto2->user_id
        ]);
        $this->expectException(AuthorizationException::class);
        $this->postService->update($dto3);
    }

    public function test_update_another_user_post_by_admin()
    {
        $this->actingAs($this->user);
        $dto1 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $postDto = $this->postService->create($dto1);

        $this->actingAs($this->admin);
        $dto2 = PostDto::fromArray([
            "id" => $postDto->id,
            "title" => "aboba title 2",
            "body" => "body abobus 2",
            "user_id" => $this->admin->id
        ]);
        $postDto2 = $this->postService->update($dto2);
        $this->assertEquals($dto2->title, $postDto2->title);
        $this->assertEquals($dto2->body, $postDto2->body);
    }

    public function test_delete_post()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $postDto = $this->postService->create($dto);
        $this->postService->delete($postDto->id);
        $postDto = $this->postService->get($postDto->id);
        $this->assertNull($postDto);
    }

    public function test_delete_not_own_post_by_user()
    {
        $this->actingAs($this->admin);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $postDto = $this->postService->create($dto);

        $this->actingAs($this->user);
        $this->expectException(AuthorizationException::class);
        $this->postService->delete($postDto->id);
    }

    public function test_delete_another_user_post_by_admin()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $postDto = $this->postService->create($dto);

        $this->actingAs($this->admin);
        $this->postService->delete($postDto->id);
        $postDto = $this->postService->get($postDto->id);
        $this->assertNull($postDto);
    }

    private function createUser(): User
    {
        $dto = UserDto::fromArray([
            "username" => "ivan",
            "email" => fake()->email(),
            "password" => "123",
            "role" => UserRole::User,
        ]);
        return $this->userService->getUser($this->userService->create($dto)->id);
    }

    private function createAdmin(): User
    {
        $dto = UserDto::fromArray([
            "username" => "ivan",
            "email" => fake()->email(),
            "password" => "123",
            "role" => UserRole::Admin,
        ]);
        return $this->userService->getUser($this->userService->create($dto)->id);
    }
}
