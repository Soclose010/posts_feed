<?php

namespace App\Filters\Post;

use App\DataTransferObjects\PipelinePostFilterDto;

class Username
{
    public function handle(PipelinePostFilterDto $dto, \Closure $next)
    {
        if ($dto->filterDto->username)
        {
            $dto->builder->where("$dto->userTable.username","ILIKE", "%{$dto->filterDto->username}%");
        }
        return $next($dto);
    }
}
