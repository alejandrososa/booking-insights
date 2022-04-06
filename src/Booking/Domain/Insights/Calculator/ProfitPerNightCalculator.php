<?php

namespace Kata\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\BookingRequest\BookingRequest;
use Kata\Booking\Domain\Insights\Pipes\Pipe;

class ProfitPerNightCalculator implements InsightCalculator
{
    public function __construct(
        private Pipe $averagePipe,
        private Pipe $minProfitPerNightPipe,
        private Pipe $maxProfitPerNightPipe,
        private Pipe $calculateProfitPerNightPipe,
    ) {
    }

    public function calculate(BookingRequest ...$requests): array
    {
        $result = $this->calculateProfitPerNightPipe->apply($requests);

        return [
            'avg' => $this->averagePipe->apply($result),
            'min' => $this->minProfitPerNightPipe->apply($result),
            'max' => $this->maxProfitPerNightPipe->apply($result),
        ];
    }
}
