<?php

namespace Kata\Common\Domain\Contracts;

abstract class PriceValueObject implements Price
{
    public function __construct(
        protected int $original,
        protected ?int $final = self::DEFAULT_AMOUNT,
        protected ?int $discountPercentage = null,
        protected ?Currency $currency = null
    ) {
    }

    public function __toString(): string
    {
        return $this->original.' '.$this->currency->code();
    }

    public static function fromInt(int $original, Currency $currency): static
    {
        return new static(
            original: $original,
            final: $original,
            currency: $currency
        );
    }

    public static function fromMoney(Price $price): static
    {
        return new static(
            original: $price->original,
            final: $price->final,
            discountPercentage: $price->discountPercentage,
            currency: $price->currency
        );
    }

    public function original(): int
    {
        return $this->original;
    }

    public function final(): ?int
    {
        return $this->final;
    }

    public function discountPercentage(): ?int
    {
        return $this->discountPercentage;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function add(Price $money): static
    {
        $this->guardCurrencyIsEquals($money);

        return new static(
            original: $this->original + $money->original,
            currency: $this->currency
        );
    }

    public function subtract(Price $money): static
    {
        $this->guardCurrencyIsEquals($money);

        return new static(
            original: $this->original - $money->original,
            currency: $this->currency
        );
    }

    public function toArray(): array
    {
        return [
            'original' => $this->original,
            'final' => $this->final,
            'discount_percentage' => !is_null($this->discountPercentage)
                ? $this->discountPercentage.'%' : null,
            'currency' => $this->currency->code(),
        ];
    }

    public function equals(Equatable $other): bool
    {
        /** @var Price $other */
        return $this->currency()->equals($other->currency()) &&
            $this->original === $other->original;
    }

    protected function guardCurrencyIsEquals(Price $money): void
    {
        /** @var self $money */
        if (!$money->currency()->equals($this->currency())) {
            throw new \InvalidArgumentException('currencies are not equals');
        }
    }
}
