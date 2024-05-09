<?php

namespace App\Services\Action;

use App\Models\Action;
use Illuminate\Database\Eloquent\Model;

class ActionService
{
    public static function write(?string $actor, string $owner, Action $action, ?Model $old, ?Model $new): void
    {
        Action::create([
            "actor" => $actor,
            "owner" => $owner,
            "action" => $action,
            "old" => $old?->toJson(),
            "new" => $new?->toJson(),
        ]);
    }
}
