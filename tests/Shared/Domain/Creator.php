<?php

namespace Kata\Tests\Shared\Domain;

use Faker\Factory;
use Faker\Generator;

final class Creator
{
    private static $faker;

    public static function random(): Generator
    {
        return self::faker();
    }

    protected static function faker(): Generator
    {
        return self::$faker = self::$faker ?: self::generator();
    }

    private static function generator(): Generator
    {
        $faker = Factory::create();

        return $faker;
    }
}
