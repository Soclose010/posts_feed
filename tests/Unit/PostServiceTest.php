<?php

namespace Tests\Unit;

use App\DataTransferObjects\PostDto;
use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Services\Post\PostService;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use DatabaseMigrations;
    private PostService $postService;
    private UserService $userService;

    private UserDto $userDto;
    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->userDto = $this->createUser();
    }

    public function test_create_post()
    {
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->userDto->id
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
        $dto1 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->userDto->id
        ]);
        $this->postService->create($dto1);
        $dto2 = PostDto::fromArray([
            "title" => "aboba title 2",
            "body" => "body abobus 2",
        ]);
        $postDto2 = $this->postService->update($dto2);
        $this->assertEquals($dto2->title, $postDto2->title);
        $this->assertEquals($dto2->body, $postDto2->body);
    }

    public function test_delete_post()
    {
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->userDto->id
        ]);
        $postDto = $this->postService->create($dto);
        $this->postService->delete($postDto->id);
        $postDto = $this->postService->get($postDto->id);
        $this->assertNull($postDto);
    }
    private function createUser(): UserDto
    {
        $dto = UserDto::fromArray([
            "username" => "ivan",
            "email" => "ivan@ivan.com",
            "password" => "123",
            "role" => UserRole::User,
        ]);
        return $this->userService->create($dto);
    }

}
