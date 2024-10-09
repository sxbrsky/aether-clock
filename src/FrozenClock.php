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

class FrozenClock implements Clock
{
    private \DateTimeImmutable $datetime;

    /**
     * @param \DateTimeImmutable|non-empty-string $datetime
     * @param \DateTimeZone|string $timezone
     *
     * @throws \DateInvalidTimeZoneException When $timezone is invalid.
     * @throws \DateMalformedStringException When $datetime is invalid.
     */
    public function __construct(
        \DateTimeImmutable|string $datetime = 'now',
        \DateTimeZone|string $timezone = Clock::DEFAULT_TIMEZONE
    ) {
        if (\is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        if (\is_string($datetime)) {
            $datetime = new \DateTimeImmutable($datetime);
        }

        $this->datetime = $datetime->setTimezone($timezone);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return \sprintf(
            '%s (%s)',
            $this->now()->format(\DateTimeInterface::ISO8601_EXPANDED),
            $this->now()->getTimezone()->getName()
        );
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function now(): \DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * {@inheritDoc}
     */
    public function withTimezone(\DateTimeZone|string $timezone): static
    {
        if (\is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        $self = clone $this;
        $self->datetime = $self->datetime->setTimezone($timezone);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function sleep(float|int $seconds): void
    {
        $wholeSeconds = \floor($seconds);
        $microSeconds = \round(($seconds - $wholeSeconds) * 1E6);

        if ($seconds > 0) {
            if (($dt = $this->datetime->modify("$wholeSeconds second")) !== false) {
                $this->datetime = $dt;
            }
        }

        if ($microSeconds > 0) {
            if (($dt = $this->datetime->modify("$microSeconds microsecond")) !== false) {
                $this->datetime = $dt;
            }
        }
    }
}
