<?php

/*
 * This file is part of the aether/aether.
 *
 * Copyright (C) 2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Aether\Clock;

use Psr\Clock\ClockInterface;

interface Clock extends ClockInterface, \Stringable
{
    /**
     * Default timezone.
     */
    public const DEFAULT_TIMEZONE = 'UTC';

    /**
     * Returns the current time as a DateTimeImmutable Object
     *
     * @throws \DateMalformedStringException When an invalid datetime string is detected.
    */
    public function now(): \DateTimeImmutable;

    /**
     * Return an instance with the specified timezone.
     *
     * @param \DateTimeZone|non-empty-string $timezone A timezone.
     * @return static
     * @throws \DateInvalidTimeZoneException When $timezone is invalid.
     */
    public function withTimezone(\DateTimeZone|string $timezone): static;

    /**
     * Delays the program execution for the given number of seconds.
     *
     * @param int|float $seconds Halt time in seconds or microseconds.
     * @return void
     */
    public function sleep(int|float $seconds): void;
}
