<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */

    'user_model' => env('USER_TIMEZONE_MODEL', 'App\\Models\\User'),

    /*
    |--------------------------------------------------------------------------
    | Database Column
    |--------------------------------------------------------------------------
    */

    'column' => env('USER_TIMEZONE_COLUMN', 'timezone'),

    /*
    |--------------------------------------------------------------------------
    | Default Fallback
    |--------------------------------------------------------------------------
    */

    'fallback' => env('USER_TIMEZONE_FALLBACK', config('app.timezone')),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => [

        'enabled' => env('USER_TIMEZONE_MIDDLEWARE_ENABLED', true),

        /*
         * Apply PHP's default timezone for the request.
         */

        'set_php_timezone' => env('USER_TIMEZONE_SET_PHP_TIMEZONE', true),

    ],

];
