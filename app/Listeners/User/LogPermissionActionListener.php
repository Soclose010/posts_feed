<?php

namespace App\Listeners\User;

use App\Events\Log\User\LogPermissionActionEvent;
use App\Services\Action\ActionServiceInterface;

class LogPermissionActionListener
{

    public function __construct(private readonly ActionServiceInterface $actionService)
    {
        //
    }

    public function handle(LogPermissionActionEvent $event): void
    {
        $userId = json_decode($event->old)->id;
            $this->actionService::write(
                $event->actorId,
                $userId,
                $event->action,
                $event->old,
                $event->new
            );
    }
}
