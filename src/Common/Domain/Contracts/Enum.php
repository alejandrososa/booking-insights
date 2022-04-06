<?php

namespace Kata\Common\Domain\Contracts;

use function Lambdish\Phunctional\reindex;
use ReflectionClass;
use Stringable;

abstract class Enum implements Stringable
{
    protected static array $cache = [];

    public function __construct(protected $value)
    {
        $this->ensureIsBetweenAcceptedValues($value);
    }

    public static function __callStatic(string $name, $args)
    {
        return new static(self::values()[$name]);
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }

    public static function fromString(string $value): Enum
    {
        return new static($value);
    }


    public static function values(): array
    {
        $class = static::class;

        if (!isset(self::$cache[$class])) {
            $reflected = new ReflectionClass($class);
            self::$cache[$class] = reindex(self::keysFormatter(), $reflected->getConstants());
        }

        return self::$cache[$class];
    }

    public function value()
    {
        return $this->value;
    }

    public function equals(Equatable $object): bool
    {
        return static::class === $object::class
            && $this === $object
            && $this->value() === $object->value();
    }

    abstract protected function throwExceptionForInvalidValue($value);

    private static function keysFormatter(): callable
    {
        return static fn ($unused, string $key): string => self::toCamelCase(strtolower($key));
    }

    private function ensureIsBetweenAcceptedValues($value): void
    {
        if (!\in_array($value, static::values(), true)) {
            $this->throwExceptionForInvalidValue($value);
        }
    }

    private static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }
}
