<?php

namespace App\Services\Post;

use App\DataTransferObjects\PipelinePostFilterDto;
use App\DataTransferObjects\PostFilterDto;
use App\DataTransferObjects\PostDto;
use App\Enums\Action;
use App\Enums\FieldName;
use App\Events\Log\Post\LogActionEvent;
use App\Filters\Post\Date;
use App\Filters\Post\Title;
use App\Filters\Post\Username;
use App\Models\Post;
use App\Models\User;
use App\Traits\FilterFieldsTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Pipeline;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class PostService
{
    use FilterFieldsTrait;

    public function create(PostDto $dto): PostDto
    {
        $post = Post::create($this->filteredFields($dto));
        LogActionEvent::dispatch(null, $post->toJson(), Action::Create);
        return PostDto::fromModel($post);
    }

    /**
     * @throws NotFound
     */
    public function update(PostDto $dto): PostDto
    {
        $post = $this->getPost($dto->id);
        $oldPost = $post->toJson();
        Gate::authorize("edit", $post->user_id);
        $post = $post->fill($this->filteredFields($dto));
        $post->update();
        LogActionEvent::dispatch($oldPost, $post->toJson(), Action::Update);
        return PostDto::fromModel($post);
    }

    /**
     * @throws NotFound
     */
    public function delete(string $id): void
    {
        $post = $this->getPost($id);
        Gate::authorize("edit", $post->user_id);
        $oldPost = $post->toJson();
        $post->delete();
        LogActionEvent::dispatch($oldPost, $post->toJson(), Action::Delete);
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
            "updated_at",
            "created_at",
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

    public function getUserPostsPaginate(int $count, string $userId): LengthAwarePaginator
    {
        $table = Post::getTableName();
        $postPaginator = Post::where("$table.user_id", $userId)->orderBy("$table.created_at", "desc")->paginate($count);
        $postPaginator->getCollection()->transform(function ($post) {
            return PostDto::fromModel($post, true);
        });
        return $postPaginator;
    }

    public function getPaginate(int $count, PostFilterDto $filterDto): LengthAwarePaginator
    {
        $postTable = Post::getTableName();
        $userTable = User::getTableName();
        $attributes = [
            "id",
            "title",
            "body",
            "user_id",
            "updated_at",
            "created_at"
        ];
        $attributes = array_map(function ($item) use ($postTable) {
            return $postTable . "." . $item;
        }, $attributes);
        $postQuery = Post::query();
        $postQuery = $postQuery->select($attributes)->username();
        $pipelineDto = PipelinePostFilterDto::fromPostFilters($filterDto, $postTable, $userTable, $postQuery);
        $postQuery = Pipeline::send($pipelineDto)
            ->through([
                Title::class,
                Username::class,
                Date::class
            ])
            ->thenReturn()->builder;
        $postPaginator = $postQuery->orderBy("$postTable.created_at", "desc")->paginate($count);
        $postPaginator->getCollection()->transform(function ($post) {
            return PostDto::fromModel($post, true);
        });
        return $postPaginator;
    }
}
