<?php

namespace Athwari\LaravelUserTimezone\Services;

use Athwari\LaravelUserTimezone\Contracts\TimezoneManager;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeZone;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class UserTimezoneManager implements TimezoneManager
{
    protected ?string $manualTimezone = null;

    public function __construct(
        protected AuthFactory $auth
    ) {}

    public function get(): string
    {
        if ($this->manualTimezone !== null) {
            return $this->manualTimezone;
        }

        return $this->resolveFromUser(
            $this->auth->guard()->user()
        );
    }

    public function resolveFromUser(?object $user): string
    {
        if ($user) {
            $column = config('user-timezone.column', 'timezone');

            $timezone = $user->{$column} ?? null;

            if ($timezone && $this->isValid($timezone)) {
                return $timezone;
            }
        }

        return $this->fallback();
    }

    public function set(string $timezone): void
    {
        if (! $this->isValid($timezone)) {
            throw new \InvalidArgumentException(
                "Invalid timezone [$timezone]."
            );
        }

        $this->manualTimezone = $timezone;
    }

    public function clear(): void
    {
        $this->manualTimezone = null;
    }

    public function isValid(string $timezone): bool
    {
        return in_array(
            $timezone,
            DateTimeZone::listIdentifiers(),
            true
        );
    }

    public function convert(CarbonInterface $date): CarbonInterface
    {
        return $date->copy()->timezone(
            $this->get()
        );
    }

    public function now(): CarbonInterface
    {
        return Carbon::now(
            $this->get()
        );
    }

    protected function fallback(): string
    {
        return config(
            'user-timezone.fallback',
            config('app.timezone')
        );
    }
}
