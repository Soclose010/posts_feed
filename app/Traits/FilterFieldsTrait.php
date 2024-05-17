<?php

namespace App\Traits;

use App\DataTransferObjects\Dto;

trait FilterFieldsTrait
{
    private function filteredFields(Dto $dto): array
    {
        return array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
    }
}
