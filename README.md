# Per-user timezone management for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/athwari/laravel-user-timezone.svg?style=flat-square)](https://packagist.org/packages/athwari/laravel-user-timezone)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/athwari/laravel-user-timezone/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/athwari/laravel-user-timezone/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/athwari/laravel-user-timezone/fix-php-code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/athwari/laravel-user-timezone/actions?query=workflow%3A"Fix+PHP+code+style"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/athwari/laravel-user-timezone?style=flat-square)](https://packagist.org/packages/athwari/laravel-user-timezone)

Store dates in UTC, display in each user's preferred timezone.

## Installation

```bash
composer require athwari/laravel-user-timezone
```

Publish the config and migration:

```bash
php artisan vendor:publish --tag=user-timezone-config
php artisan vendor:publish --tag=user-timezone-migrations
php artisan migrate
```

## Configuration

```php
// config/user-timezone.php
return [
    'user_model' => env('USER_TIMEZONE_MODEL', 'App\\Models\\User'),
    'column' => env('USER_TIMEZONE_COLUMN', 'timezone'),
    'fallback' => env('USER_TIMEZONE_FALLBACK', config('app.timezone')),
    'middleware' => [
        'enabled' => env('USER_TIMEZONE_MIDDLEWARE_ENABLED', true),
        'set_php_timezone' => env('USER_TIMEZONE_SET_PHP_TIMEZONE', true),
    ],
];
```

## Usage

### Facade

```php
use Athwari\LaravelUserTimezone\Facades\UserTimezone;

UserTimezone::get();                          // 'Europe/London'
UserTimezone::resolveFromUser($user);         // 'Europe/London' or fallback
UserTimezone::set('America/New_York');        // override for request
UserTimezone::clear();                        // remove override
UserTimezone::now();                          // Carbon in user's timezone
UserTimezone::convert($post->created_at);     // Carbon converted to user's timezone
UserTimezone::isValid('Invalid/Zone');        // false
```

### Helper

```php
user_timezone(); // string
```

### Middleware

The `ApplyUserTimezone` middleware is registered automatically. It resolves the user's timezone and sets both `app.timezone` and PHP's default timezone for the request lifecycle.

### Model Trait

Add `HasTimezone` to your User model:

```php
use Athwari\LaravelUserTimezone\Traits\HasTimezone;

class User extends Authenticatable
{
    use HasTimezone;
}
```

```php
$user->getTimezone();              // 'Europe/London' or fallback
$user->setTimezone('Asia/Tokyo');  // validates and sets
$user->timezone = 'UTC';           // validated attribute mutator
```

### Opt-in Date Conversion

For models where you want explicit timezone-aware accessors:

```php
use Athwari\LaravelUserTimezone\Traits\ConvertsDatesToUserTimezone;

class Post extends Model
{
    use ConvertsDatesToUserTimezone;
}
```

```php
$post->inUserTimezone('created_at'); // Carbon in user's timezone
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for details.

## License

MIT
