<?php

namespace Athwari\LaravelUserTimezone\Tests\Unit;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Athwari\LaravelUserTimezone\Tests\TestCase;
use Athwari\LaravelUserTimezone\Traits\ConvertsDatesToUserTimezone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConvertsDatesToUserTimezoneTest extends TestCase
{
    public function test_it_converts_carbon_attribute_to_user_timezone()
    {
        $this->app->make(TimezoneManager::class)->set('America/New_York');

        $model = new class() extends Model
        {
            use ConvertsDatesToUserTimezone;
        };

        $model->setAttribute('starts_at', Carbon::parse('2025-06-15 12:00:00', 'UTC'));

        $converted = $model->inUserTimezone('starts_at');

        $this->assertEquals('America/New_York', $converted->timezoneName);
        $this->assertEquals('2025-06-15 08:00:00', $converted->format('Y-m-d H:i:s'));
    }

    public function test_it_returns_null_for_non_carbon_attribute()
    {
        $model = new class() extends Model
        {
            use ConvertsDatesToUserTimezone;
        };

        $model->setAttribute('starts_at', '2025-06-15 12:00:00');

        $this->assertNull($model->inUserTimezone('starts_at'));
    }
}
