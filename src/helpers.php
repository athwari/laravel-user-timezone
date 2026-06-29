<?php

use Athwari\LaravelUserTimezone\Facades\UserTimezone;

if (! function_exists('user_timezone')) {
    function user_timezone(): string
    {
        return UserTimezone::get();
    }
}
