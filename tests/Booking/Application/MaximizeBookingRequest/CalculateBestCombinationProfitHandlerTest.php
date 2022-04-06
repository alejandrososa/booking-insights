<?php

namespace Kata\Tests\Booking\Application\MaximizeBookingRequest;

use Kata\Booking\Application\MaximizeBookingRequest\CalculateBestCombinationProfitHandler;
use Kata\Booking\Application\MaximizeBookingRequest\MaximizeTotalProfitsResponse;
use Kata\Tests\Booking\BookingInfrastructureTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;
use Kata\Tests\Shared\Domain\NumberMother;

class CalculateBestCombinationProfitHandlerTest extends BookingInfrastructureTestCase
{
    private ?CalculateBestCombinationProfitHandler $sut = null;

    protected function setUp(): void
    {
        $this->sut = new CalculateBestCombinationProfitHandler(
            $this->maximizeTotalProfitsCalculator(),
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testItShouldCalculateAListOfBookingRequestsAndReturnProductsResponse()
    {
        $query = CalculateBestCombinationProfitMother::create();

        $avg = NumberMother::create();
        $min = NumberMother::create();
        $max = NumberMother::create();
        $totalProfit = NumberMother::create();
        $requestIds = [
            BookingRequestMother::create()->requestId(),
            BookingRequestMother::create()->requestId(),
        ];

        $this->maximizeTotalProfitsCalculatorShouldCalculateAndReturn([
            'request_ids' => $requestIds,
            'total_profit' => $totalProfit,
            'avg' => $avg,
            'min' => $min,
            'max' => $max,
        ]);

        $result = $this->sut->__invoke($query);

        $this->eventually(fn() => $this->assertInstanceOf(MaximizeTotalProfitsResponse::class, $result));
        $this->eventually(fn() => $this->assertEquals($requestIds, $result->requestIds()));
        $this->eventually(fn() => $this->assertEquals($totalProfit, $result->totalProfit()));
        $this->eventually(fn() => $this->assertEquals($avg, $result->avg()));
        $this->eventually(fn() => $this->assertEquals($min, $result->min()));
        $this->eventually(fn() => $this->assertEquals($max, $result->max()));
    }
}
