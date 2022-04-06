<?php
namespace Kata\Common\Domain\Contracts;

interface Price extends ValueObject
{
    public const DEFAULT_AMOUNT = 0;

    public function original(): ?int;

    public function final(): ?int;

    public function discountPercentage(): ?int;

    public function currency(): Currency;

    public function toArray(): array;

    public static function fromMoney(Price $money): Price;

    public function add(Price $money);

    public function subtract(Price $money);
}
