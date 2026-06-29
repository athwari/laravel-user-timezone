<?php

declare(strict_types=1);

namespace Athwari\LaravelUserTimezone\Facades;

use Athwari\LaravelUserTimezone\Services\UserTimezoneManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string get()
 * @method static string resolveFromUser(object|null $user)
 * @method static void set(string $timezone)
 * @method static void clear()
 * @method static bool isValid(string $timezone)
 * @method static \Carbon\CarbonInterface convert(\Carbon\CarbonInterface $date)
 * @method static \Carbon\CarbonInterface now()
 *
 * @see UserTimezoneManager
 */
class UserTimezone extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'user-timezone';
    }
}
