<?php

namespace Kata\Booking\Application\StatsBookingRequest;

final class ProfitPerNightResponse
{
    public function __construct(
        private float $avg,
        private float $min,
        private float $max,
    ) {
    }

    public function avg(): float
    {
        return $this->avg;
    }

    public function min(): float
    {
        return $this->min;
    }

    public function max(): float
    {
        return $this->max;
    }
}
