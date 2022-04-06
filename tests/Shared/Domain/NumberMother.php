<?php

namespace Kata\Tests\Shared\Domain;

class NumberMother
{
    public static function create(?string $number = null): string
    {
        return $number ?? Creator::random()->numberBetween(1, 100);
    }

    public static function between(?string $first = null, ?string $last = null): string
    {
        return $number ?? Creator::random()->numberBetween($first, $last);
    }
}
