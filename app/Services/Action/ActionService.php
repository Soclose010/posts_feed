<?php

namespace App\Services\Action;

use App\Enums\Action as ActionEnum;
use App\Models\Action;

class ActionService implements ActionServiceInterface
{
    public static function write(?string $actor, string $owner, ActionEnum $action, ?string $old, ?string $new): void
    {
        Action::create([
            "actor" => $actor,
            "owner" => $owner,
            "action" => $action,
            "old" => $old,
            "new" => $new,
        ]);
    }
}
