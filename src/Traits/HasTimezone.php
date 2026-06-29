<?php

namespace Athwari\LaravelUserTimezone\Traits;

use DateTimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use InvalidArgumentException;

trait HasTimezone
{
    public function getTimezoneColumn(): string
    {
        return config('user-timezone.column', 'timezone');
    }

    public function getTimezone(): string
    {
        $column = $this->getTimezoneColumn();

        $timezone = $this->{$column};

        return $timezone ?: config(
            'user-timezone.fallback',
            config('app.timezone')
        );
    }

    public function setTimezone(string $timezone): static
    {
        if (! in_array($timezone, DateTimeZone::listIdentifiers(), true)) {
            throw new InvalidArgumentException(
                "Invalid timezone [{$timezone}] supplied."
            );
        }

        $column = $this->getTimezoneColumn();

        $this->{$column} = $timezone;

        return $this;
    }

    protected function timezone(): Attribute
    {
        return new Attribute(
            set: function (?string $value) {
                if ($value === null) {
                    return null;
                }

                if (! in_array($value, DateTimeZone::listIdentifiers(), true)) {
                    throw new InvalidArgumentException(
                        "Invalid timezone [{$value}]"
                    );
                }

                return $value;
            }
        );
    }
}
