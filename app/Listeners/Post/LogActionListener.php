<?php

namespace App\Listeners\Post;

use App\Enums\Action;
use App\Events\Log\Post\LogActionEvent;
use App\Services\Action\ActionServiceInterface;
use Illuminate\Support\Facades\Auth;

class LogActionListener
{
    public function __construct(private readonly ActionServiceInterface $actionService)
    {
        //
    }
    public function handle(LogActionEvent $event): void
    {

        $params = [
            "actor" => Auth::id(),
            "owner" => Auth::id(),
            "action" => $event->action,
            "old" => $event->old,
            "new" => $event->new
        ];
        switch ($event->action) {
            case Action::Create:
                $this->actionService::write(...array_values($params));
                break;
            case Action::Update:
            case Action::Delete:
                $userId = json_decode($event->old)->user_id;
                $params["owner"] = $userId;
                $this->actionService::write(...array_values($params));
                break;
        }
    }
}
