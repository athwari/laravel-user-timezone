<?php

namespace Athwari\LaravelUserTimezone;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Athwari\LaravelUserTimezone\Http\Middleware\ApplyUserTimezone;
use Athwari\LaravelUserTimezone\Services\UserTimezoneManager;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Support\ServiceProvider;

class UserTimezoneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/user-timezone.php',
            'user-timezone'
        );

        $this->app->singleton(
            'user-timezone',
            UserTimezoneManager::class
        );

        $this->app->alias(
            'user-timezone',
            TimezoneManager::class
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/user-timezone.php' => config_path('user-timezone.php'),
        ], 'user-timezone-config');

        $this->publishes([
            __DIR__.'/../database/migrations/add_timezone_to_users_table.php.stub' => database_path(
                'migrations/'.date('Y_m_d_His').'_add_timezone_to_users_table.php'
            ),
        ], 'user-timezone-migrations');

        if (config('user-timezone.middleware.enabled')) {
            $kernel = $this->app->make(HttpKernel::class);

            if (method_exists($kernel, 'pushMiddleware')) {
                $kernel->pushMiddleware(ApplyUserTimezone::class);
            }
        }
    }
}
