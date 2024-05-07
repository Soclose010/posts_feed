<?php

namespace App\DataTransferObjects;

use Illuminate\Database\Eloquent\Model;

abstract class Dto
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    abstract public static function fromModel(Model $model): static;

    abstract public static function fromArray(array $data): static;
}
