<?php

namespace Kata\Booking\Application\MaximizeBookingRequest;

use Kata\Booking\Domain\BookingRequest\BookingRequestFactory;
use Kata\Booking\Domain\Insights\Calculator\InsightCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateBestCombinationProfitHandler
{
    public function __construct(private InsightCalculator $maximizeTotalProfitsCalculator)
    {
    }

    public function __invoke(CalculateBestCombinationProfit $query): MaximizeTotalProfitsResponse
    {
        $bookingRequests = BookingRequestFactory::fromArray($query->getData());
        $result = $this->maximizeTotalProfitsCalculator->calculate(...$bookingRequests);
        [
            'request_ids' => $requestIds,
            'total_profit' => $totalProfit,
            'avg' => $avg,
            'min' => $min,
            'max' => $max,
        ] = $result;

        return new MaximizeTotalProfitsResponse(
            requestId: $requestIds,
            totalProfit: $totalProfit,
            avg: $avg,
            min: $min,
            max: $max
        );
    }
}
