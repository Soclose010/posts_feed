<?php

namespace App\DataTransferObjects;


use Illuminate\Database\Eloquent\Builder;

class PipelinePostFilterDto
{
    public readonly PostFilterDto $filterDto;
    public readonly string $postTable;
    public readonly string $userTable;
    public readonly Builder $builder;

    public static function fromPostFilters(PostFilterDto $filterDto, string $postTable, string $userTable, Builder $builder): PipelinePostFilterDto
    {
        $dto = new static();
        $dto->filterDto = $filterDto;
        $dto->postTable = $postTable;
        $dto->userTable = $userTable;
        $dto->builder = $builder;
        return $dto;
    }
}
