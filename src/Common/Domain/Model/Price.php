<?php

namespace Kata\Common\Domain\Model;

use Kata\Common\Domain\Contracts\PriceValueObject;

class Price extends PriceValueObject
{
    public function applyPercentDiscount(int $percentage)
    {
        $discount = $this->calculateDiscount($percentage);
        $priceDiscount = Price::fromInt($discount, $this->currency());
        $priceDiscountApplied = $this->subtract($priceDiscount);

        return new self(
            original: $this->original(),
            final: $priceDiscountApplied->original(),
            discountPercentage: $percentage,
            currency: $this->currency()
        );
    }

    private function calculateDiscount(int $percentage): int
    {
        return intval((abs($percentage / 100)) * $this->original());
    }
}
