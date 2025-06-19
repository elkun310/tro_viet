<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
        Gate::define('host', function ($user) {
            return $user->isHost();
        });
        Gate::define('user', function ($user) {
            return $user->isUser();
        });
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
        Gate::define('manage-posts', function ($user) {
            return $user->isAdmin() || $user->isHost();
        });
    }
}
