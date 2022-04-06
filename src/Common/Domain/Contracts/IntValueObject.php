<?php

namespace Kata\Common\Domain\Contracts;

use Stringable;

abstract class IntValueObject implements Stringable
{
    public function __construct(protected int $value)
    {
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $object): bool
    {
        return static::class === $object::class
            && $this === $object
            && $this->value() === $object->value();
    }
}
