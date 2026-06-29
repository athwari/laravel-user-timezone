<?php

namespace Athwari\LaravelUserTimezone\Traits;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Carbon\CarbonInterface;

trait ConvertsDatesToUserTimezone
{
    public function inUserTimezone(string $attribute): ?CarbonInterface
    {
        $value = $this->getAttribute($attribute);

        if (! $value instanceof CarbonInterface) {
            return null;
        }

        return app(TimezoneManager::class)->convert($value);
    }
}
