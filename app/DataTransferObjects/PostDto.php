<?php

namespace App\DataTransferObjects;

use App\Models\Post;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class PostDto extends Dto
{
    public readonly ?string $id;
    public readonly ?string $title;
    public readonly ?string $body;
    public readonly ?string $user_id;
    public readonly ?CarbonInterface $created_at;
    public readonly ?CarbonInterface $updated_at;

    private function __construct()
    {
    }

    public static function fromCreateRequest(Request $request, string $user_id): self
    {
        $dto = new self();
        $dto->title = $request->validated("title");
        $dto->body = $request->validated("body");
        $dto->user_id = $user_id;
        return $dto;
    }

    public static function fromModel(Post $post): self
    {
        $dto = new self();
        $dto->id = $post->id;
        $dto->title = $post->title;
        $dto->body = $post->body;
        $dto->user_id = $post->user_id;
        $dto->created_at = $post->created_at;
        $dto->updated_at = $post->updated_at;
        return $dto;
    }

    public static function fromUpdateRequest(Request $request, string $id, string $user_id): self
    {
        $dto = new self();
        $dto->id = $id;
        $dto->title = $request->validated("title");
        $dto->body = $request->validated("title");
        $dto->user_id = $user_id;
        return $dto;
    }

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->id = $data["id"] ?? null;
        $dto->title = $data["title"] ?? null;
        $dto->body = $data["body"] ?? null;
        $dto->user_id = $data["user_id"] ?? null;
        $dto->created_at = $data["created_at"] ?? null;
        $dto->updated_at = $data["updated_at"] ?? null;
        return $dto;
    }
}
