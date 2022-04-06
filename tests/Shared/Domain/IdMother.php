<?php

namespace Kata\Tests\Shared\Domain;

class IdMother
{
    public static function create(?string $text = null): string
    {
        return $text ?? Creator::random()->numerify('#####_#####');
    }
}
