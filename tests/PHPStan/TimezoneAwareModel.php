<?php

declare(strict_types=1);

namespace Athwari\LaravelUserTimezone\Tests\PHPStan;

use Athwari\LaravelUserTimezone\Traits\ConvertsDatesToUserTimezone;
use Athwari\LaravelUserTimezone\Traits\HasTimezone;
use Illuminate\Database\Eloquent\Model;

class TimezoneAwareModel extends Model
{
    use ConvertsDatesToUserTimezone;
    use HasTimezone;
}
