<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\MinProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Tests\Booking\BookingUnitTestCase;

class MinProfitPerNightPipeTest extends BookingUnitTestCase
{
    private ?MinProfitPerNightPipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new MinProfitPerNightPipe();
    }

    public function testItMustBeInstanceOfPipe()
    {
        $this->assertInstanceOf(Pipe::class, $this->sut);
    }

    public function numberProvider()
    {
        return [
            'zero' => [[8], 8],
            'first' => [[8, 12], 8],
            'second' => [[10, 20], 10],
            'third' => [[50, 100], 50],
            'fourth' => [[50, 100, 75, 12], 12],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnTheMinValue(
        array $numbers,
        float $expected
    ) {
        $this->assertEquals($expected, $this->sut->apply($numbers));
    }
}
