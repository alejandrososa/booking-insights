<?php

namespace Kata\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\BookingRequest\BookingRequest;
use Kata\Booking\Domain\Insights\Pipes\Pipe;

use function Lambdish\Phunctional\{filter, first, map};

class MaximizeTotalProfitsCalculator implements InsightCalculator
{
    public function __construct(
        private Pipe $cleanOverlapsPipe,
        private Pipe $searchAllCombinationsPipe,
        private InsightCalculator $profitPerNightCalculator
    ) {
    }

    public function calculate(BookingRequest ...$requests): array
    {
        $result = $this->getTheBestCombinationsWithoutOverlaps(...$requests);

        $stats = $this->profitPerNightCalculator->calculate(...$result['items']);
        $requestIds = $this->getRequestIds(...$result['items']);

        return [
            'request_ids' => $requestIds,
            'total_profit' => $result['profit'] ?? 0,
            'avg' => $stats['avg'] ?? 0,
            'min' => $stats['min'] ?? 0,
            'max' => $stats['max'] ?? 0,
        ];
    }

    private function getRequestIds(BookingRequest ...$requests): array
    {
        return map(fn(BookingRequest $request) => $request->requestId(), $requests);
    }

    private function getTheBestCombinationsWithoutOverlaps(BookingRequest ...$requests): array
    {
        $requestWithoutOverlap = $this->cleanOverlapsPipe->apply($requests);
        $allCombinations = $this->searchAllCombinationsPipe->apply($requests);

        return first(filter(fn($result) => $result['items'] == $requestWithoutOverlap, $allCombinations));
    }
}
