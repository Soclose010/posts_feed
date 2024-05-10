<?php

namespace App\Services\Action;

use App\Enums\Action as ActionEnum;
use Illuminate\Database\Eloquent\Model;

interface ActionServiceInterface
{
    public static function write(?string $actor, string $owner, ActionEnum $action, ?Model $old, ?Model $new): void;
}
