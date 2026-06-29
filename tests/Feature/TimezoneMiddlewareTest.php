<?php

namespace Athwari\LaravelUserTimezone\Tests\Feature;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Athwari\LaravelUserTimezone\Http\Middleware\ApplyUserTimezone;
use Athwari\LaravelUserTimezone\Tests\TestCase;
use Illuminate\Http\Request;

class TimezoneMiddlewareTest extends TestCase
{
    public function test_middleware_resolves_timezone_and_sets_config()
    {
        $manager = $this->app->make(TimezoneManager::class);
        $manager->set('Europe/London');

        $middleware = new ApplyUserTimezone($manager);
        $request = new Request();

        $response = $middleware->handle($request, fn ($req) => 'next');

        $this->assertEquals('next', $response);
        $this->assertEquals('Europe/London', config('app.timezone'));
    }

    public function test_middleware_uses_fallback_for_guest()
    {
        $manager = $this->app->make(TimezoneManager::class);
        $middleware = new ApplyUserTimezone($manager);
        $request = new Request();

        $middleware->handle($request, function ($req) {
            //
        });

        $this->assertEquals('UTC', config('app.timezone'));
    }

    public function test_middleware_can_be_disabled()
    {
        config(['user-timezone.middleware.enabled' => false]);

        $manager = $this->app->make(TimezoneManager::class);
        $manager->set('Asia/Tokyo');

        $middleware = new ApplyUserTimezone($manager);
        $request = new Request();

        $middleware->handle($request, function ($req) {
            //
        });

        $this->assertNotEquals('Asia/Tokyo', config('app.timezone'));
    }
}
