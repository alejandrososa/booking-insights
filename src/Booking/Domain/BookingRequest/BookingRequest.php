<?php

namespace Kata\Booking\Domain\BookingRequest;

use Kata\Common\Domain\Contracts\Entity;
use Kata\Common\Domain\Contracts\Equatable;

class BookingRequest implements Entity
{
    public const FORMAT_YMD = 'Y-m-d';

    private function __construct(
        private string $requestId,
        private \DateTime $checkIn,
        private int $nights,
        private int $sellingRate,
        private int $margin,
    ) {
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function checkIn(): \DateTime
    {
        return $this->checkIn;
    }

    public function nights(): int
    {
        return $this->nights;
    }

    public function sellingRate(): int
    {
        return $this->sellingRate;
    }

    public function margin(): int
    {
        return $this->margin;
    }

    public function toArray(): array
    {
        return [
            'request_id' => $this->requestId(),
            'check_in' => $this->checkIn()->format(self::FORMAT_YMD),
            'nights' => $this->nights(),
            'selling_rate' => $this->sellingRate(),
            'margin' => $this->margin(),
        ];
    }

    public function equals(Equatable $other): bool
    {
        /* @var self $other */
        return get_class($this) === get_class($other)
            && $this->requestId() === $other->requestId();
    }

    public static function create(
        string $requestId,
        string $checkIn,
        int $nights,
        int $sellingRate,
        int $margin,
    ): self {
        return new self (
            requestId: $requestId,
            checkIn: \DateTime::createFromFormat(self::FORMAT_YMD, $checkIn),
            nights: $nights,
            sellingRate: $sellingRate,
            margin: $margin,
        );
    }
}