<?php

namespace Kata\Tests\Common\Domain\Criteria;

use Kata\Common\Domain\Criteria\Filter;
use Kata\Common\Domain\Criteria\Filters;

final class FiltersMother
{
    /** @param Filter[] $filters */
    public static function create(array $filters): Filters
    {
        return new Filters($filters);
    }

    public static function createOne(Filter $filter): Filters
    {
        return self::create([$filter]);
    }

    public static function blank(): Filters
    {
        return self::create([]);
    }
}
