<?php

namespace Athwari\LaravelUserTimezone\Http\Middleware;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Closure;
use Illuminate\Http\Request;

class ApplyUserTimezone
{
    public function __construct(
        protected TimezoneManager $manager
    ) {}

    public function handle(
        Request $request,
        Closure $next
    ) {
        if (! config('user-timezone.middleware.enabled')) {
            return $next($request);
        }

        $timezone = $this->manager->get();

        if (config('user-timezone.middleware.set_php_timezone')) {
            date_default_timezone_set($timezone);
        }

        config([
            'app.timezone' => $timezone,
        ]);

        return $next($request);
    }
}
