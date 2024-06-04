<?php

namespace App\Providers;

use App\Events\Log\Post\LogActionEvent;
use App\Events\Log\User\LogActionEvent as LogActionEventAlias;
use App\Events\Log\User\LogPermissionActionEvent;
use App\Listeners\Post\LogActionListener;
use App\Listeners\User\LogActionListener as LogActionListenerAlias;
use App\Listeners\User\LogPermissionActionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LogActionEvent::class => [LogActionListener::class],
        LogActionEventAlias::class => [LogActionListenerAlias::class],
        LogPermissionActionEvent::class => [LogPermissionActionListener::class]
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
