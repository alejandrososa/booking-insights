<?php

namespace Kata\Common\Domain\Contracts;

abstract class StringValueObject implements ValueObject
{
    public function __construct(protected string $value)
    {
    }

    public static function fromString(mixed $value): static
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Equatable $object): bool
    {
        return static::class === $object::class
            && $this->value() === $object->value();
    }
}
