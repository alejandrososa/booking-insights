<?php

namespace Kata\Tests\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\Insights\Pipes\AveragePipe;
use Kata\Booking\Domain\Insights\Pipes\Pipe;
use Kata\Tests\Booking\BookingUnitTestCase;

class AveragePipeTest extends BookingUnitTestCase
{
    private ?AveragePipe $sut = null;

    protected function setUp(): void
    {
        $this->sut = new AveragePipe();
    }

    public function testItMustBeInstanceOfPipe()
    {
        $this->assertInstanceOf(Pipe::class, $this->sut);
    }

    public function numberProvider()
    {
        return [
            'zero' => [[8], 8],
            'first' => [[8, 12], 10],
            'second' => [[10, 20], 15],
            'third' => [[50, 100], 75],
            'fourth' => [[50, 100, 75, 12], 59.25],
        ];
    }

    /** @dataProvider numberProvider */
    public function testItShouldReturnTheAverageOfManyValues(
        array $numbers,
        float $expected
    ) {
        $this->assertEquals($expected, $this->sut->apply($numbers));
    }
}
