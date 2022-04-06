<?php

namespace Kata\Tests\Booking\Domain\BookingRequest;

use Kata\Common\Domain\Contracts\Entity;
use Kata\Booking\Domain\BookingRequest\BookingRequest;
use Kata\Tests\Booking\BookingUnitTestCase;

class BookingRequestTest extends BookingUnitTestCase
{
    public const ID = 'bookata_XY123';
    private ?BookingRequest $sut = null;

    protected function setUp(): void
    {
        $this->sut = BookingRequest::create(
            requestId: self::ID,
            checkIn: '2020-01-01',
            nights: 5,
            sellingRate: 200,
            margin: 20
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testItMustBeInstanceOfEntity()
    {
        $this->assertInstanceOf(Entity::class, $this->sut);
    }

    public function testItMustChangeToArray()
    {
        $this->assertIsArray($this->sut->toArray());
    }

    public function productProvider()
    {
        return [
            'equals' => [self::ID, '2020-01-01', 5, 200, 20, true],
            'not equals' => ['bookata_XYBBB', '2020-01-01', 5, 300, 10, false],
        ];
    }

    /** @dataProvider productProvider */
    public function testItMustValidateIfIsEqualToAnotherObject(
        string $id,
        string $checkIn,
        int $nights,
        int $sellingRate,
        int $margin,
        bool $expected,
    ) {
        $product = BookingRequest::create($id, $checkIn, $nights, $sellingRate, $margin);
        $this->assertEquals($expected, $this->sut->equals($product));
    }
}
