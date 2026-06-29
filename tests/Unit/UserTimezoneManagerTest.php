<?php

namespace Athwari\LaravelUserTimezone\Tests\Unit;

use Athwari\LaravelUserTimezone\Services\UserTimezoneManager;
use Athwari\LaravelUserTimezone\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;

class UserTimezoneManagerTest extends TestCase
{
    protected UserTimezoneManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->make(UserTimezoneManager::class);
    }

    public function test_it_returns_fallback_when_no_user()
    {
        $this->assertEquals('UTC', $this->manager->get());
    }

    public function test_it_returns_authenticated_user_timezone()
    {
        $manager = $this->managerForUser((object) [
            'timezone' => 'Europe/Berlin',
        ]);

        $this->assertEquals('Europe/Berlin', $manager->get());
    }

    public function test_it_resolves_timezone_from_user_directly()
    {
        $this->assertEquals(
            'Europe/Berlin',
            $this->manager->resolveFromUser((object) [
                'timezone' => 'Europe/Berlin',
            ])
        );
    }

    public function test_it_resolves_timezone_from_configured_user_column()
    {
        config(['user-timezone.column' => 'preferred_timezone']);

        $this->assertEquals(
            'Asia/Tokyo',
            $this->manager->resolveFromUser((object) [
                'preferred_timezone' => 'Asia/Tokyo',
            ])
        );
    }

    public function test_it_returns_fallback_when_resolving_null_user()
    {
        $this->assertEquals('UTC', $this->manager->resolveFromUser(null));
    }

    public function test_it_returns_fallback_when_authenticated_user_timezone_is_invalid()
    {
        $manager = $this->managerForUser((object) [
            'timezone' => 'Not/A-Timezone',
        ]);

        $this->assertEquals('UTC', $manager->get());
    }

    public function test_it_can_set_and_get_timezone()
    {
        $this->manager->set('America/New_York');

        $this->assertEquals('America/New_York', $this->manager->get());
    }

    public function test_it_throws_on_invalid_timezone()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->manager->set('Invalid/Timezone');
    }

    public function test_it_validates_timezones()
    {
        $this->assertTrue($this->manager->isValid('UTC'));
        $this->assertTrue($this->manager->isValid('Europe/London'));
        $this->assertFalse($this->manager->isValid('Not/A-Timezone'));
    }

    public function test_it_converts_carbon_instance()
    {
        $this->manager->set('America/New_York');

        $date = Carbon::parse('2025-06-15 12:00:00', 'UTC');
        $converted = $this->manager->convert($date);

        $this->assertEquals('America/New_York', $converted->timezoneName);
        $this->assertEquals('UTC', $date->timezoneName);
    }

    public function test_it_returns_now_in_active_timezone()
    {
        $this->manager->set('Asia/Tokyo');

        $now = $this->manager->now();

        $this->assertEquals('Asia/Tokyo', $now->timezoneName);
    }

    public function test_it_clears_manual_timezone()
    {
        $this->manager->set('Europe/Paris');
        $this->assertEquals('Europe/Paris', $this->manager->get());

        $this->manager->clear();
        $this->assertEquals('UTC', $this->manager->get());
    }

    private function managerForUser(object $user): UserTimezoneManager
    {
        $guard = $this->createMock(Guard::class);
        $guard->method('user')->willReturn($user);

        $auth = $this->createMock(Factory::class);
        $auth->method('guard')->willReturn($guard);

        return new UserTimezoneManager($auth);
    }
}
