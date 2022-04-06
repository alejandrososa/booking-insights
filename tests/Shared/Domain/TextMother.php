<?php

namespace Kata\Tests\Shared\Domain;

class TextMother
{
    public static function create(?string $text = null): string
    {
        return $text ?? Creator::random()->text(10);
    }
}
