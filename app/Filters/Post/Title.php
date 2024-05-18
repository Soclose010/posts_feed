<?php

namespace App\Filters\Post;

use App\DataTransferObjects\PipelinePostFilterDto;

class Title
{
    public function handle(PipelinePostFilterDto $dto, \Closure $next)
    {
        if ($dto->filterDto->title)
        {
            $dto->builder->where("$dto->postTable.title","ILIKE", "%{$dto->filterDto->title}%");
        }
        return $next($dto);
    }
}
