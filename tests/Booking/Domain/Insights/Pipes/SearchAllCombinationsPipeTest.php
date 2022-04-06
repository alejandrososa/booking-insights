<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Booking\Domain\Insights\Pipes\SearchAllCombinationsPipe;
use Kata\Tests\Booking\BookingUnitTestCase;
use Kata\Tests\Booking\Domain\BookingRequest\BookingRequestMother;

class SearchAllCombinationsPipeTest extends BookingUnitTestCase
{
    private ?SearchAllCombinationsPipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new SearchAllCombinationsPipe();
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
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20],
                    ['id' => 'kayete_PP234','selling_rate' => 156, 'margin' => 5],
                    ['id' => 'atropote_AA930','selling_rate' => 150, 'margin' => 6],
                    ['id' => 'acme_AAAAA','selling_rate' => 150, 'margin' => 30],
                ],
                16,
            ],
            'one' => [
                [
                    ['id' => 'bookata_XY123','selling_rate' => 200, 'margin' => 20],
                    ['id' => 'kayete_PP234','selling_rate' => 156, 'margin' => 5],
                ],
                4,
            ],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnManyCombinationsAsAPossible(
        array $requests,
        int $totalCombinationsExpected
    ) {
        $bookingRequests = array_map(fn($request) => BookingRequestMother::create(
            requestId: $request['id'],
            sellingRate: $request['selling_rate'],
            margin: $request['margin'],
        ), $requests);

        $this->assertCount($totalCombinationsExpected, $this->sut->apply($bookingRequests));
    }
}
