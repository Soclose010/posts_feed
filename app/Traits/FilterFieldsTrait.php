<?php

namespace App\Traits;

use App\DataTransferObjects\Dto;

trait FilterFieldsTrait
{
    private function fieldsToUpdate(Dto $dto): array
    {
        return array_filter($dto->toArray(), function ($value) {
            return $value !== null;
        });
    }
}
