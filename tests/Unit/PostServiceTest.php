<?php

namespace Tests\Unit;

use App\DataTransferObjects\PostDto;
use App\DataTransferObjects\PostFilterDto;
use App\DataTransferObjects\UserDto;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Action\ActionServiceInterface;
use App\Services\Post\PostService;
use App\Services\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Spatie\FlareClient\Http\Exceptions\NotFound;
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
        $this->mock(ActionServiceInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive("write")->andReturns();
        });
        $this->postService = $this->app->make(PostService::class);
        $this->userService = $this->app->make(UserService::class);
        $this->user = $this->createUser();
        $this->admin = $this->createAdmin();
    }

    public function test_get_post()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $createdPostDto = $this->postService->create($dto);
        $postDto = $this->postService->get($createdPostDto->id);
        $this->assertEquals($createdPostDto, $postDto);
    }

    public function test_get_non_exist_post()
    {
        $this->actingAs($this->user);
        $id = Str::uuid();
        $this->expectException(NotFound::class);
        $this->postService->get($id);
    }

    public function test_get_post_with_username()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $createdPostDto = $this->postService->create($dto);
        $postDto = $this->postService->getWithUsername($createdPostDto->id);
        $this->assertEquals($dto->title, $postDto->title);
        $this->assertEquals($dto->body, $postDto->body);
        $this->assertEquals($dto->user_id, $postDto->user_id);
        $this->assertEquals($this->user->username, $postDto->username);
    }

    public function test_get_non_exist_post_with_username()
    {
        $this->actingAs($this->user);
        $id = Str::uuid();
        $this->expectException(NotFound::class);
        $this->postService->getWithUsername($id);
    }

    public function test_get_user_posts()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title 1",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 2",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 3",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);

        $postsPaginator = $this->postService->getUserPostsPaginate(2, $this->user->id);
        $this->assertCount(2, $postsPaginator->getCollection());
    }

    public function test_get_posts()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title 1",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 2",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 3",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);

        $this->actingAs($this->admin);
        $dto = PostDto::fromArray([
            "title" => "aboba title 1",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 2",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "aboba title 3",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $dto = PostFilterDto::fromArray([]);
        $postPaginator = $this->postService->getPaginate(4, $dto);
        $this->assertCount(4, $postPaginator->getCollection());
    }

    public function test_get_posts_with_filters()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "pepega",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "sus",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);

        $this->actingAs($this->admin);
        $dto = PostDto::fromArray([
            "title" => "aboba",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "pepega",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $dto = PostDto::fromArray([
            "title" => "sus",
            "body" => "body abobus",
            "user_id" => $this->admin->id
        ]);
        $this->postService->create($dto);
        $postFilterDto = PostFilterDto::fromArray([
            "title" => "a",
            "username" => $this->user->username
        ]);
        $postPaginator = $this->postService->getPaginate(6, $postFilterDto);
        $this->assertCount(2, $postPaginator->getCollection());
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
            "editorId" => $this->user->id,
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
            "user_id" => $this->admin->id,
            "editorId" => $this->user->id
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
            "editorId" => $this->admin->id
        ]);
        $postDto2 = $this->postService->update($dto2);
        $this->assertEquals($dto2->title, $postDto2->title);
        $this->assertEquals($dto2->body, $postDto2->body);
    }

    public function test_update_non_exist_post()
    {
        $this->actingAs($this->user);
        $dto1 = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto1);
        $id = Str::uuid();
        $dto2 = PostDto::fromArray([
            "id" => $id,
            "title" => "aboba title 2",
            "body" => "body abobus 2",
            "editorId" => $this->user->id,
        ]);
        $this->expectException(NotFound::class);
        $this->postService->update($dto2);
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
        $this->expectException(NotFound::class);
        $this->postService->get($postDto->id);
    }

    public function test_delete_non_exist_post()
    {
        $this->actingAs($this->user);
        $dto = PostDto::fromArray([
            "title" => "aboba title",
            "body" => "body abobus",
            "user_id" => $this->user->id
        ]);
        $this->postService->create($dto);
        $id = Str::uuid();
        $this->expectException(NotFound::class);
        $this->postService->delete($id);
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
        $this->expectException(NotFound::class);
        $this->postService->get($postDto->id);
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
            "username" => "oleg",
            "email" => fake()->email(),
            "password" => "123",
            "role" => UserRole::Admin,
        ]);
        return $this->userService->getUser($this->userService->create($dto)->id);
    }
}
