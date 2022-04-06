<?php

namespace Kata\Booking\Application\StatsBookingRequest;

class CalculateProfitPerNight
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
