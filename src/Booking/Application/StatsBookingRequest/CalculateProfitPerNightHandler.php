<?php

namespace Kata\Booking\Application\StatsBookingRequest;

use Kata\Booking\Domain\BookingRequest\BookingRequestFactory;
use Kata\Booking\Domain\Insights\Calculator\InsightCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateProfitPerNightHandler
{
    public function __construct(private InsightCalculator $profitPerNightCalculator)
    {
    }

    public function __invoke(CalculateProfitPerNight $query): ProfitPerNightResponse
    {
        $bookingRequests = BookingRequestFactory::fromArray($query->getData());
        $result = $this->profitPerNightCalculator->calculate(...$bookingRequests);
        ['avg' => $avg, 'min' => $min, 'max' => $max] = $result;

        return new ProfitPerNightResponse($avg, $min, $max);
    }
}
