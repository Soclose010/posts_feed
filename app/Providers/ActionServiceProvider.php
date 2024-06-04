<?php

namespace App\Providers;

use App\Services\Action\ActionService;
use App\Services\Action\ActionServiceInterface;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ActionServiceInterface::class, ActionService::class);
    }

    public function boot(): void
    {
        //
    }
}
