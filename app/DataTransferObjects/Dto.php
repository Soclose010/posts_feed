<?php

namespace App\DataTransferObjects;

abstract class Dto
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    abstract public static function fromArray(array $data): self;
}
