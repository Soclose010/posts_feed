<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        Gate::define('edit-user', function (User $authUser, User $editedUser) {
            return $authUser->isAdmin() || $authUser->id === $editedUser->id;
        });

        Gate::define('edit-post', function (User $authUser, Post $editedPost) {
            return $authUser->isAdmin() || $authUser->id === $editedPost->user_id;
        });
    }
}
