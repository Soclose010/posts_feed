<?php

namespace App\Services\Post;

use App\DataTransferObjects\PostDto;
use App\Enums\Action;
use App\Enums\FieldName;
use App\Exceptions\ExistedEmailException;
use App\Models\Post;
use App\Services\Action\ActionServiceInterface;
use App\Traits\FilterFieldsTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class PostService
{
    use FilterFieldsTrait;

    public function __construct(private readonly ActionServiceInterface $actionService)
    {
    }

    public function create(PostDto $dto): PostDto
    {
        $post = Post::create($this->filteredFields($dto));
        $this->actionService::write(
            Auth::id(),
            Auth::id(),
            Action::Create,
            null,
            $post->toJson()
        );
        return PostDto::fromModel($post);
    }

    /**
     * @throws ExistedEmailException
     * @throws NotFound
     */
    public function update(PostDto $dto): PostDto
    {
        $post = $this->getPost($dto->id);
        $oldPost = $post->toJson();
        Gate::authorize("edit", $post->user_id);
        try {
            $post = tap($post->fill($this->filteredFields($dto)))->save();
        } catch (UniqueConstraintViolationException) {
            throw new ExistedEmailException();
        }
        $this->actionService::write(
            Auth::id(),
            $post->user_id,
            Action::Update,
            $oldPost,
            $post->toJson()
        );
        return PostDto::fromModel($post);
    }

    /**
     * @throws NotFound
     */
    public function delete(string $id): void
    {
        $post = $this->getPost($id);
        Gate::authorize("edit", $post->user_id);
        $oldPost = clone $post;
        $post->delete();
        $this->actionService::write(
            Auth::id(),
            $oldPost->user_id,
            Action::Delete,
            $oldPost->toJson(),
            null
        );
    }

    /**
     * @throws NotFound
     */
    public function get(string $value, FieldName $field = FieldName::Id): PostDto
    {
        return PostDto::fromModel($this->getPost($value, $field));
    }

    /**
     * @throws NotFound
     */
    public function getPost(string $id, FieldName $field = FieldName::Id): Post
    {
        if (!$post = Post::where($field->value, $id)->first()) {
            throw new NotFound();
        }
        return $post;
    }

    public function getWithUsername(string $value, FieldName $field = FieldName::Id): PostDto
    {
        $table = Post::getTableName();
        $attributes = [
            "title",
            "body",
            "user_id",
            "updated_at"
        ];
        $attributes = array_map(function ($item) use ($table) {
            return $table . "." . $item;
        }, $attributes);
        if (!$post = Post::select($attributes)->where("posts." . $field->value, $value)->username()->first())
        {
            throw new NotFound();
        }
        return PostDto::fromModel($post, true);
    }

    public function getUserPosts(string $userId): Collection
    {
        $posts = Post::where("posts.user_id", $userId)->get();
        return Collection::make($posts)->map(function ($post) {
            return PostDto::fromModel($post);
        });
    }

    public function getPaginate(int $count): LengthAwarePaginator
    {
        $table = Post::getTableName();
        $attributes = [
            "id",
            "title",
            "body",
            "user_id",
            "updated_at",
            "created_at"
        ];
        $attributes = array_map(function ($item) use ($table) {
            return $table . "." . $item;
        }, $attributes);
        $postPaginator = Post::select($attributes)->username()->orderBy("$table.created_at", "desc")->paginate($count);
        $postPaginator->getCollection()->transform(function ($post) {
            return PostDto::fromModel($post, true);
        });
        return $postPaginator;
    }
}
