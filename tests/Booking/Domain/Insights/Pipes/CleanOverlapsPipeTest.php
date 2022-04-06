<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\CleanOverlapsPipe;
use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Tests\Booking\BookingUnitTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;

class CleanOverlapsPipeTest extends BookingUnitTestCase
{
    private ?CleanOverlapsPipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new CleanOverlapsPipe();
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
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20, 'nights' => 5, 'check_in' => '2020-01-01'],
                    ['id' => 'kayete_PP234','selling_rate' => 156, 'margin' => 5, 'nights' => 4, 'check_in' => '2020-01-04'],
                    ['id' => 'atropote_AA930','selling_rate' => 150, 'margin' => 6, 'nights' => 4, 'check_in' => '2020-01-04'],
                    ['id' => 'acme_AAAAA','selling_rate' => 150, 'margin' => 30, 'nights' => 4, 'check_in' => '2020-01-10'],
                ],
                [
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20, 'nights' => 5, 'check_in' => '2020-01-01'],
                    ['id' => 'acme_AAAAA','selling_rate' => 150, 'margin' => 30, 'nights' => 4, 'check_in' => '2020-01-10'],
                ],
            ],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnRequestWithoutOverlaps(
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

        $bookingRequestExpected = array_map(fn($request) => BookingRequestMother::create(
            requestId: $request['id'],
            checkIn: $request['check_in'],
            nights: $request['nights'],
            sellingRate: $request['selling_rate'],
            margin: $request['margin'],
        ), $expected);

        $this->assertEquals($bookingRequestExpected, $this->sut->apply($bookingRequests));
    }
}
