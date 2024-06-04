<?php

namespace App\Listeners\User;

use App\Enums\Action;
use App\Events\Log\User\LogActionEvent;
use App\Services\Action\ActionServiceInterface;
use Illuminate\Support\Facades\Auth;

class LogActionListener
{
    public function __construct(private readonly ActionServiceInterface $actionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LogActionEvent $event): void
    {
        $params = [
            "actor" => Auth::id(),
            "owner" => "",
            "action" => $event->action,
            "old" => $event->old,
            "new" => $event->new
        ];
        switch ($event->action) {
            case Action::Create:
                $userId = json_decode($event->new)->id;
                $params["actor"] = $userId;
                $params["owner"] = $userId;
                $this->actionService::write(...array_values($params));
                break;
            case Action::Update:
            case Action::Delete:
                $userId = json_decode($event->old)->id;
                $params["owner"] = $userId;
                $this->actionService::write(...array_values($params));
                break;
        }
    }
}
