<?php

namespace Kata\Tests\Booking\Application\StatsBookingRequest;

use Kata\Booking\Application\StatsBookingRequest\CalculateProfitPerNight;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;
use Kata\Tests\Shared\Domain\{DateMother, NumberMother, TextMother};

class CalculateProfitPerNightMother
{
    public static function create(?array $data = null): CalculateProfitPerNight
    {
        $fakeRequest = [
            "request_id" => "bookata_XY123",
            "check_in" => "2020-01-01",
            "nights" => 1,
            "selling_rate" => 50,
            "margin" => 20,
        ];

        return new CalculateProfitPerNight(
            array_map(
                fn($request) => BookingRequestMother::create(
                    requestId: $request['request_id'] ?? TextMother::create(),
                    checkIn: $request['check_in'] ?? DateMother::create(),
                    nights: $request['nights'] ?? NumberMother::between(1, 15),
                    sellingRate: $request['selling_rate'] ?? NumberMother::between(100, 300),
                    margin: $request['margin'] ?? NumberMother::between(1, 40)
                )->toArray(),
                $data ?? $fakeRequest
            )
        );
    }
}
