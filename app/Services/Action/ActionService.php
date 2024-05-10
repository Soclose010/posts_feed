<?php

namespace App\Services\Action;

use App\Enums\Action as ActionEnum;
use App\Models\Action;
use Illuminate\Database\Eloquent\Model;

class ActionService implements ActionServiceInterface
{
    public static function write(?string $actor, string $owner, ActionEnum $action, ?Model $old, ?Model $new): void
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
