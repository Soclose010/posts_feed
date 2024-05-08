<?php

namespace App\DataTransferObjects;

use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Models\Post;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class PostDto extends Dto
{
    public readonly ?string $id;
    public readonly ?string $title;
    public readonly ?string $body;
    public readonly ?string $user_id;
    public readonly ?string $editorId;
    public readonly ?CarbonInterface $created_at;
    public readonly ?CarbonInterface $updated_at;

    private function __construct()
    {
    }

    public static function fromCreateRequest(PostCreateRequest $request, string $userId): static
    {
        $dto = new static();
        $dto->title = $request->validated("title");
        $dto->body = $request->validated("body");
        $dto->user_id = $userId;
        return $dto;
    }

    public static function fromModel(Model $model): static
    {
        if (!$model instanceof Post) {
            throw new InvalidArgumentException();
        }
        $dto = new static();
        $dto->id = $model->id;
        $dto->title = $model->title;
        $dto->body = $model->body;
        $dto->user_id = $model->user_id;
        $dto->created_at = $model->created_at;
        $dto->updated_at = $model->updated_at;
        return $dto;
    }

    public static function fromUpdateRequest(PostUpdateRequest $request, string $id, string $editorId): static
    {
        $dto = new static();
        $dto->id = $id;
        $dto->title = $request->validated("title");
        $dto->body = $request->validated("title");
        $dto->editorId = $editorId;
        return $dto;
    }

    public static function fromArray(array $data): static
    {
        $dto = new static();
        $dto->id = $data["id"] ?? null;
        $dto->title = $data["title"] ?? null;
        $dto->body = $data["body"] ?? null;
        $dto->user_id = $data["user_id"] ?? null;
        $dto->created_at = $data["created_at"] ?? null;
        $dto->updated_at = $data["updated_at"] ?? null;
        $dto->editorId = $data["editorId"] ?? null;
        return $dto;
    }
}
