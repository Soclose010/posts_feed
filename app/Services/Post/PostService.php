<?php

namespace App\Services\Post;

use App\DataTransferObjects\PostDto;
use App\Exceptions\ExistedEmailException;
use App\Models\Post;
use App\Traits\FilterFieldsTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;

class PostService
{
    use FilterFieldsTrait;
    public function create(PostDto $dto): PostDto
    {
        $user = Post::create($this->fieldsToUpdate($dto));
        return PostDto::fromModel($user);
    }

    /**
     * @throws ExistedEmailException
     */
    public function update(PostDto $dto): PostDto
    {
        $post = $this->getPost($dto->id);
        Gate::authorize("edit", $post->user_id);
        try {
            $post = tap($post->fill($this->fieldsToUpdate($dto)))->save();
        }
        catch (UniqueConstraintViolationException)
        {
            throw new ExistedEmailException();
        }
        return PostDto::fromModel($post);
    }

    public function delete(string $id): void
    {
        $post = $this->getPost($id);
        Gate::authorize("edit", $post->user_id);
        $post->delete();
    }

    public function get(string $id): ?PostDto
    {
        if ($post = Post::where("id", $id)->first()) {
            return PostDto::fromModel($post);
        }
        return null;
    }

    public function getPost(string $id): ?Post
    {
        return Post::find($id);
    }
}
