<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\CalculateProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Tests\Booking\BookingUnitTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;

class CalculateProfitPerNightTest extends BookingUnitTestCase
{
    private ?CalculateProfitPerNightPipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new CalculateProfitPerNightPipe();
    }

    public function testItMustBeInstanceOfPipe()
    {
        $this->assertInstanceOf(Pipe::class, $this->sut);
    }

    public function numberProvider()
    {
        return [
            'zero' => [
                [
                    ['selling_rate' => 200, 'margin' => 20, 'nights' => 5],
                    ['selling_rate' => 156, 'margin' => 22, 'nights' => 4],
                ],
                [8.0, 8.58],
            ],
            'one' => [
                [
                    ['selling_rate' => 50, 'margin' => 20, 'nights' => 1],
                    ['selling_rate' => 55, 'margin' => 22, 'nights' => 1],
                    ['selling_rate' => 49, 'margin' => 21, 'nights' => 1],
                ],
                [10.0, 12.1, 10.29],
            ],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnStatsProfitPerNight(
        array $requests,
        array $expected
    ) {
        $bookingRequests = array_map(fn($request) => BookingRequestMother::create(
            nights: $request['nights'],
            sellingRate: $request['selling_rate'],
            margin: $request['margin'],
        ), $requests);

        $this->assertEquals($expected, $this->sut->apply($bookingRequests));
    }
}
