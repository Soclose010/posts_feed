<?php

namespace App\Providers;

use App\Services\Action\ActionService;
use App\Services\Action\ActionServiceInterface;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ActionServiceInterface::class, ActionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
