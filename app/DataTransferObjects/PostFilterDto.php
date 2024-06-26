<?php

namespace App\DataTransferObjects;

use App\Http\Requests\Post\PostFilterRequest;

class PostFilterDto
{
    public readonly ?string $title;
    public readonly ?string $username;
    public readonly ?string $date;

    public static function fromFilterRequest(PostFilterRequest $request): PostFilterDto
    {
        return self::fromArray($request->validated());
    }

    public static function fromArray(array $data): PostFilterDto
    {
        $dto = new static();
        $dto->title = $data['title'] ?? '';
        $dto->username = $data['username'] ?? '';
        $dto->date = $data['date'] ?? '';
        return $dto;
    }
}
