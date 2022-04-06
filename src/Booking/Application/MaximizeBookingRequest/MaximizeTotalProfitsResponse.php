<?php

namespace Kata\Booking\Application\MaximizeBookingRequest;

final class MaximizeTotalProfitsResponse
{
    public function __construct(
        private array $requestId,
        private float $totalProfit,
        private float $avg,
        private float $min,
        private float $max,
    ) {
    }

    public function requestIds(): array
    {
        return $this->requestId;
    }

    public function totalProfit(): float
    {
        return $this->totalProfit;
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
