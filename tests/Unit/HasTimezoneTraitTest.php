<?php

namespace Athwari\LaravelUserTimezone\Tests\Unit;

use Athwari\LaravelUserTimezone\Tests\TestCase;
use Athwari\LaravelUserTimezone\Tests\User;
use InvalidArgumentException;

class HasTimezoneTraitTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function test_it_returns_column_name()
    {
        $this->assertEquals('timezone', $this->user->getTimezoneColumn());
    }

    public function test_it_returns_fallback_when_no_timezone_set()
    {
        $this->assertEquals('UTC', $this->user->getTimezone());
    }

    public function test_it_sets_and_gets_timezone()
    {
        $this->user->setTimezone('America/Chicago');

        $this->assertEquals('America/Chicago', $this->user->getTimezone());
    }

    public function test_it_throws_on_invalid_timezone_set()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user->setTimezone('Not/A-Timezone');
    }

    public function test_it_validates_via_attribute_mutator()
    {
        $this->user->timezone = 'Europe/Berlin';

        $this->assertEquals('Europe/Berlin', $this->user->timezone);
    }

    public function test_attribute_mutator_rejects_invalid_timezone()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->user->timezone = '';
    }

    public function test_attribute_mutator_allows_null()
    {
        $this->user->timezone = null;

        $this->assertNull($this->user->timezone);
    }
}
