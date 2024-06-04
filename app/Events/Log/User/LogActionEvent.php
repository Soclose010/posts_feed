<?php

namespace App\Events\Log\User;

use App\Enums\Action;
use Illuminate\Foundation\Events\Dispatchable;


class LogActionEvent
{
    use Dispatchable;

    public function __construct(
        public ?string $old,
        public ?string $new,
        public Action  $action
    )
    {
    }
}
