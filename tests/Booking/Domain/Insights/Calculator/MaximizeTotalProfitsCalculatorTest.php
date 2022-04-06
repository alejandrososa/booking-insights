<?php

namespace Kata\Tests\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\Insights\Calculator\InsightCalculator;
use Kata\Booking\Domain\Insights\Calculator\MaximizeTotalProfitsCalculator;
use Kata\Booking\Domain\Insights\Pipes\CleanOverlapsPipe;
use Kata\Booking\Domain\Insights\Pipes\SearchAllCombinationsPipe;
use Kata\Tests\Booking\BookingUnitTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;

class MaximizeTotalProfitsCalculatorTest extends BookingUnitTestCase
{
    private ?MaximizeTotalProfitsCalculator $sut = null;

    protected function setUp(): void
    {
        $this->sut = new MaximizeTotalProfitsCalculator(
            new CleanOverlapsPipe(),
            new SearchAllCombinationsPipe(),
            ProfitPerNightCalculatorMother::create(),
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
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20, 'nights' => 5, 'check_in' => '2020-01-01'],
                    ['id' => 'kayete_PP234','selling_rate' => 156, 'margin' => 5, 'nights' => 4, 'check_in' => '2020-01-04'],
                    ['id' => 'atropote_AA930','selling_rate' => 150, 'margin' => 6, 'nights' => 4, 'check_in' => '2020-01-04'],
                    ['id' => 'acme_AAAAA','selling_rate' => 160, 'margin' => 30, 'nights' => 4, 'check_in' => '2020-01-10'],
                ],
                [
                    'request_ids' => ['bookata_XY123', 'acme_AAAAA'],
                    'total_profit' => 88,
                    'avg' => 10,
                    'min' => 8,
                    'max' => 12
                ],
            ],
            'one' => [
                [
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20, 'nights' => 5, 'check_in' => '2020-01-01'],
                    ['id' => 'acme_AAAAA','selling_rate' => 160, 'margin' => 30, 'nights' => 4, 'check_in' => '2020-01-10'],
                    ['id' => 'acme_BBBBB','selling_rate' => 160, 'margin' => 30, 'nights' => 4, 'check_in' => '2020-01-15'],
                ],
                [
                    'request_ids' => ['bookata_XY123', 'acme_AAAAA', 'acme_BBBBB'],
                    'total_profit' => 136,
                    'avg' => 10.67,
                    'min' => 8,
                    'max' => 12
                ],
            ],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldCalculateARequestListAndReturnTheMaxTotalProfits(
        array $requests,
        array $expected
    ) {
        $bookingRequests = array_map(fn($request) => BookingRequestMother::create(
            requestId: $request['id'],
            checkIn: $request['check_in'],
            nights: $request['nights'],
            sellingRate: $request['selling_rate'],
            margin: $request['margin'],
        ), $requests);

        $this->assertEquals($expected, $this->sut->calculate(...$bookingRequests));
    }
}
