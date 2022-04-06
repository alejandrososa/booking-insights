<?php

namespace Kata\Tests\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\Insights\Calculator\InsightCalculator;
use Kata\Booking\Domain\Insights\Calculator\ProfitPerNightCalculator;
use Kata\Booking\Domain\Insights\Pipes\AveragePipe;
use Kata\Booking\Domain\Insights\Pipes\CalculateProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\MaxProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\MinProfitPerNightPipe;
use Kata\Tests\Booking\BookingUnitTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;

class ProfitPerNightCalculatorTest extends BookingUnitTestCase
{
    private ?ProfitPerNightCalculator $sut = null;

    protected function setUp(): void
    {
        $this->sut = new ProfitPerNightCalculator(
            new AveragePipe(),
            new MinProfitPerNightPipe(),
            new MaxProfitPerNightPipe(),
            new CalculateProfitPerNightPipe(),
        );
    }

    public function testItMustBeInstanceOfInsightCalculator()
    {
        $this->assertInstanceOf(InsightCalculator::class, $this->sut);
    }

    public function numberProvider()
    {
        return [
            'zero' => [
                [
                    ['id' => 'bookata_XY123', 'selling_rate' => 50, 'margin' => 20, 'nights' => 1],
                    ['id' => 'kayete_PP234', 'selling_rate' => 55, 'margin' => 22, 'nights' => 1],
                    ['id' => 'trivoltio_ZX69', 'selling_rate' => 49, 'margin' => 21, 'nights' => 1],
                ],
                [
                    'avg' => 10.80,
                    'min' => 10.0,
                    'max' => 12.1,
                ],
            ],
            'one' => [
                [
                    ['id' => 'bookata_XY123', 'selling_rate' => 200, 'margin' => 20, 'nights' => 5],
                    ['id' => 'acme_AAAAA', 'selling_rate' => 156, 'margin' => 22, 'nights' => 4],
                ],
                [
                    'avg' => 8.29,
                    'min' => 8,
                    'max' => 8.58,
                ],
            ],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldCalculateARequestListAndReturnItsProfitPerNight(
        array $requests,
        array $expected
    ) {
        $bookingRequests = array_map(fn($request) => BookingRequestMother::create(
            requestId: $request['id'],
            nights: $request['nights'],
            sellingRate: $request['selling_rate'],
            margin: $request['margin'],
        ), $requests);

        $this->assertEquals($expected, $this->sut->calculate(...$bookingRequests));
    }
}
