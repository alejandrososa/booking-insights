<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\MaxProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Tests\Booking\BookingUnitTestCase;

class MaxProfitPerNightPipeTest extends BookingUnitTestCase
{
    private ?MaxProfitPerNightPipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new MaxProfitPerNightPipe();
    }

    public function testItMustBeInstanceOfPipe()
    {
        $this->assertInstanceOf(Pipe::class, $this->sut);
    }

    public function numberProvider()
    {
        return [
            'zero' => [[8], 8],
            'first' => [[8, 12], 12],
            'second' => [[10, 20], 20],
            'third' => [[50, 100], 100],
            'fourth' => [[50, 100, 75, 12], 100],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnTheMaxValue(
        array $numbers,
        float $expected
    ) {
        $this->assertEquals($expected, $this->sut->apply($numbers));
    }
}
