<?php

namespace Kata\Tests\Booking\Application\StatsBookingRequest;

use Kata\Booking\Application\StatsBookingRequest\CalculateProfitPerNightHandler;
use Kata\Booking\Application\StatsBookingRequest\ProfitPerNightResponse;
use Kata\Tests\Booking\BookingInfrastructureTestCase;
use Kata\Tests\Shared\Domain\NumberMother;

class CalculateProfitPerNightHandlerTest extends BookingInfrastructureTestCase
{
    private ?CalculateProfitPerNightHandler $sut = null;

    protected function setUp(): void
    {
        $this->sut = new CalculateProfitPerNightHandler(
            $this->profitPerNightCalculator(),
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testItShouldCalculateAListOfBookingRequestsAndReturnProductsResponse()
    {
        $query = CalculateProfitPerNightMother::create();

        $avg = NumberMother::create();
        $min = NumberMother::create();
        $max = NumberMother::create();

        $this->profitPerNightCalculatorShouldCalculateAndReturn([
            'avg' => $avg,
            'min' => $min,
            'max' => $max,
        ]);

        $result = $this->sut->__invoke($query);

        $this->eventually(fn() => $this->assertInstanceOf(ProfitPerNightResponse::class, $result));
        $this->eventually(fn() => $this->assertEquals($avg, $result->avg()));
        $this->eventually(fn() => $this->assertEquals($min, $result->min()));
        $this->eventually(fn() => $this->assertEquals($max, $result->max()));
    }
}
