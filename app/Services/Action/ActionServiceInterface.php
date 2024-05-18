<?php

namespace App\Services\Action;

use App\Enums\Action as ActionEnum;

interface ActionServiceInterface
{
    public static function write(?string $actor, string $owner, ActionEnum $action, ?string $old, ?string $new): void;
}
