<?php

namespace Kata\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\BookingRequest\BookingRequest;

interface InsightCalculator
{
    public function calculate(BookingRequest ...$requests): array;
}
