<?php

/*
 * This file is part of the sxbrsky/clock.
 *
 * Copyright (C) 2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Sxbrsky\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Sxbrsky\Clock\Clock;
use Sxbrsky\Clock\FrozenClock;

#[CoversClass(FrozenClock::class)]

class FrozenClockTest extends TestCase
{
    public function testInstanceOfClockInterface(): void
    {
        $clock = new FrozenClock();

        self::assertInstanceOf(Clock::class, $clock);
        self::assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testWithTimeZone(): void
    {
        $clock = new FrozenClock();
        $newClock = $clock->withTimeZone(new \DateTimeZone('Europe/Warsaw'));

        self::assertNotSame($newClock, $clock);
        self::assertSame('Europe/Warsaw', $newClock->now()->getTimezone()->getName());
    }

    public function testTimeDoesNotChange(): void
    {
        $clock = new FrozenClock();

        $first = $clock->now()->format('U.u');
        \usleep(10);
        $second = $clock->now()->format('U.u');

        self::assertSame($first, $second);
    }

    public function testSleep(): void
    {
        $clock = new FrozenClock('2024-01-27 23:53:00.999Z');
        $clock->sleep(2.002001);

        self::assertSame(
            '2024-01-27 23:53:03.001001',
            $clock->now()->format('Y-m-d H:i:s.u')
        );
    }
}
