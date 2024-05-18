<?php

namespace App\Filters\Post;

use App\DataTransferObjects\PipelinePostFilterDto;

class Date
{
    public function handle(PipelinePostFilterDto $dto, \Closure $next)
    {
        if ($dto->filterDto->date)
        {
            $dto->builder->whereDate("$dto->postTable.created_at","=", "{$dto->filterDto->date}");
        }
        return $next($dto);
    }
}
