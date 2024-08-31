<?php

/*
 * This file is part of the sxbrsky/clock.
 *
 * Copyright (C) 2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Sxbrsky\Clock;

use DateTimeImmutable;

class SystemClock implements Clock {
  private \DateTimeZone $timezone;

  /**
   * @param \DateTimeZone|string $timezone
   * @throws \DateInvalidTimeZoneException
   */
  public function __construct(\DateTimeZone|string $timezone = Clock::DEFAULT_TIMEZONE) {
    $this->timezone = \is_string($timezone) ? $this->withTimezone($timezone)->timezone : $timezone;
  }

  /**
   * {@inheritDoc}
   */
  public function now(): DateTimeImmutable {
    return new \DateTimeImmutable('now', $this->timezone);
  }

  /**
   * {@inheritDoc}
   */
  public function withTimezone(\DateTimeZone|string $timezone): static {
    if (\is_string($timezone)) {
      try {
        $timezone = new \DateTimeZone($timezone);
      } catch (\Exception $e) {
        throw new \DateInvalidTimeZoneException($e->getMessage(), $e->getCode(), $e);
      }
    }

    $clone = clone $this;
    $clone->timezone = $timezone;

    return $clone;
  }

  /**
   * {@inheritDoc}
   */
  public function sleep(float|int $seconds): void {
    if (0 < $s = (int) $seconds) {
      \sleep($s);
    }

    if (0 < $us = $seconds - $s) {
      \usleep((int) ($us * 1E6));
    }
  }

}
