<?php

declare(strict_types=1);

namespace Athwari\LaravelUserTimezone\Contracts;

use Carbon\CarbonInterface;

interface TimezoneManager
{
    public function get(): string;

    public function resolveFromUser(?object $user): string;

    /**
     * @throws \InvalidArgumentException
     */
    public function set(string $timezone): void;

    public function clear(): void;

    public function isValid(string $timezone): bool;

    public function convert(CarbonInterface $date): CarbonInterface;

    public function now(): CarbonInterface;
}
