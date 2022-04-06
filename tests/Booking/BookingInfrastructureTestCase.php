<?php

namespace Kata\Tests\Booking;

use Kata\Booking\Domain\Insights\Calculator\InsightCalculator;
use Kata\Tests\Shared\Infrastructure\PhpUnit\InfrastructureTestCase;
use PHPUnit\Framework\MockObject\MockObject;

abstract class BookingInfrastructureTestCase extends InfrastructureTestCase
{
    private InsightCalculator|MockObject|null $profitPerNightCalculator;
    private InsightCalculator|MockObject|null $maximizeTotalProfitsCalculator;

    protected function profitPerNightCalculator(): InsightCalculator|MockObject
    {
        return $this->profitPerNightCalculator = $this->profitPerNightCalculator
            ?? $this->createMock(InsightCalculator::class);
    }

    protected function maximizeTotalProfitsCalculator(): InsightCalculator|MockObject
    {
        return $this->maximizeTotalProfitsCalculator = $this->maximizeTotalProfitsCalculator
            ?? $this->createMock(InsightCalculator::class);
    }

    protected function profitPerNightCalculatorShouldCalculateAndReturn(array $data)
    {
        $this->profitPerNightCalculator()
            ->method('calculate')
            ->willReturn($data);
    }

    protected function maximizeTotalProfitsCalculatorShouldCalculateAndReturn(array $data)
    {
        $this->maximizeTotalProfitsCalculator()
            ->method('calculate')
            ->willReturn($data);
    }
}
