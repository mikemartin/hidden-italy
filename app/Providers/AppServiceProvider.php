<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Statamic\Statamic;
use Statamic\Events\UserRegistered;
use App\Listeners\SyncGuestLikes;

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
        Statamic::vite('app', [
            'input' => [
                'resources/js/cp.js',
                'resources/css/cp.css',
            ],
            'buildDirectory' => 'vendor/app',
        ]);

        // Statamic::vite('app', [
        //     'resources/js/cp.js',
        //     'resources/css/cp.css',
        // ]);

        // Listen for user registration and login events to sync guest likes
        Event::listen(UserRegistered::class, SyncGuestLikes::class);
        Event::listen(Login::class, SyncGuestLikes::class);
    }
}
