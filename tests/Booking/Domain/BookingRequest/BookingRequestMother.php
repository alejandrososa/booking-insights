<?php

namespace Kata\Tests\Booking\Domain\BookingRequest;

use Kata\Booking\Domain\BookingRequest\BookingRequest;
use Kata\Tests\Shared\Domain\DateMother;
use Kata\Tests\Shared\Domain\NumberMother;
use Kata\Tests\Shared\Domain\TextMother;

class BookingRequestMother
{
    public static function create(
        ?string $requestId = null,
        ?string $checkIn = null,
        ?int $nights = null,
        ?int $sellingRate = null,
        ?int $margin = null
    ): BookingRequest {
        return BookingRequest::create(
            requestId: $requestId ?? TextMother::create(),
            checkIn: $checkIn ?? DateMother::create(),
            nights: $nights ?? NumberMother::between(1, 15),
            sellingRate: $sellingRate ?? NumberMother::between(100, 300),
            margin: $margin ?? NumberMother::between(1, 40)
        );
    }
}
