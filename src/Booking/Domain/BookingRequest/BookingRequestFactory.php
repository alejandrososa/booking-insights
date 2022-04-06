<?php

namespace Kata\Booking\Domain\BookingRequest;

use function Lambdish\Phunctional\map;

class BookingRequestFactory
{
    public static function fromArray(array $requests): array
    {
        return map(fn($booking) => BookingRequest::create(
            requestId: $booking['request_id'],
            checkIn: $booking['check_in'],
            nights: $booking['nights'],
            sellingRate: $booking['selling_rate'],
            margin: $booking['margin'],
        ), $requests);
    }
}
