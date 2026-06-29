<?php

namespace Athwari\LaravelUserTimezone\Tests\Feature;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Athwari\LaravelUserTimezone\Facades\UserTimezone;
use Athwari\LaravelUserTimezone\Services\UserTimezoneManager;
use Athwari\LaravelUserTimezone\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_it_registers_user_timezone_singleton()
    {
        $instance = $this->app->make('user-timezone');

        $this->assertInstanceOf(UserTimezoneManager::class, $instance);
    }

    public function test_it_binds_timezone_manager_contract()
    {
        $instance = $this->app->make(TimezoneManager::class);

        $this->assertInstanceOf(UserTimezoneManager::class, $instance);
    }

    public function test_singleton_returns_same_instance()
    {
        $first = $this->app->make('user-timezone');
        $second = $this->app->make('user-timezone');

        $this->assertSame($first, $second);
    }

    public function test_facade_resolves_correctly()
    {
        $this->assertInstanceOf(
            UserTimezoneManager::class,
            UserTimezone::getFacadeRoot()
        );
    }

    public function test_helper_returns_current_user_timezone()
    {
        UserTimezone::set('Asia/Tokyo');

        $this->assertEquals('Asia/Tokyo', user_timezone());
    }

    public function test_config_supports_environment_backed_defaults()
    {
        $this->assertEquals('App\\Models\\User', config('user-timezone.user_model'));
        $this->assertEquals('timezone', config('user-timezone.column'));
        $this->assertTrue(config('user-timezone.middleware.enabled'));
        $this->assertTrue(config('user-timezone.middleware.set_php_timezone'));
    }
}
