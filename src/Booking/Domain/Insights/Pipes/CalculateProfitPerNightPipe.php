<?php

namespace Kata\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\BookingRequest\BookingRequest;

use function Lambdish\Phunctional\map;

class CalculateProfitPerNightPipe implements Pipe
{
    public function apply(array $data): mixed
    {
        return map(
            fn(BookingRequest $request) => $this->calculateProfit(
                sellingRate: $request->sellingRate(),
                margin: $request->margin(),
                nights: $request->nights()
            ),
            $data
        );
    }

    private function calculateProfit($sellingRate, $margin, $nights): float
    {
        return (float)(($sellingRate * $margin) / 100) / $nights;
    }
}
