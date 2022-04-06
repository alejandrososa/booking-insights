<?php

namespace Kata\Tests\Shared\Domain;

use Kata\Booking\Domain\BookingRequest\BookingRequest;

class DateMother
{
    public static function create(?string $number = null): string
    {
        return $number ?? Creator::random()
                ->dateTimeThisMonth()
                ->format(BookingRequest::FORMAT_YMD);
    }
}
