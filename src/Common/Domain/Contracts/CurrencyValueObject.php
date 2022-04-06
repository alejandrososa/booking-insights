<?php
namespace Kata\Common\Domain\Contracts;

use Assert\Assertion;
use Assert\AssertionFailedException;

abstract class CurrencyValueObject implements Currency
{
    protected $code;

    public function __construct(?string $code = null)
    {
        if (empty($code)) {
            $code = self::DEFAULT_CODE;
        }
        $this->guard($code);
        $this->code = $code;
    }

    public static function fromString(?string $currency): static
    {
        return new static($currency);
    }

    public static function generate(): static
    {
        return new static(self::DEFAULT_CODE);
    }

    private function guard(?string $code): void
    {
        if (empty($code)) {
            return;
        }
        try {
            Assertion::regex($code, '/^[A-Z]{3}$/', 'invalid currency');
            Assertion::maxLength($code, 3, 'invalid currency');
            Assertion::string($code, 'invalid currency');
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function equals(Equatable $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code();
    }
}
