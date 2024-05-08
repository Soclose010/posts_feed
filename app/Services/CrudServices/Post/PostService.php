<?php

namespace App\Services\CrudServices\Post;

use App\DataTransferObjects\Dto;
use App\DataTransferObjects\PostDto;
use App\Models\Post;
use App\Services\Action\ActionService;
use App\Services\CrudServices\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostService extends CrudService
{

    public function __construct()
    {
        parent::__construct(Post::class, PostDto::class);
    }

    public function update(Dto $dto): Dto
    {
        $post = $this->getModel($dto->id);
        Gate::authorize("edit", $post->user_id);
        $oldPost = clone $post;
        $post = tap($post->fill($this->fieldsForOperation($dto)))->save();
        ActionService::write(
            Auth::id(),
            $this->getOwner($post),
            "update",
            $oldPost,
            $post
        );
        return $this->dto::fromModel($post);
    }
    public function delete(string $id): void
    {
        $post = $this->getModel($id);
        Gate::authorize("edit", $post->user_id);
        $post->delete();
        ActionService::write(
            Auth::id(),
            $this->getOwner($post),
            "delete",
            $post,
            null
        );
    }

    public function getOwner(Model $model): string
    {
        return $model->user_id;
    }
}
