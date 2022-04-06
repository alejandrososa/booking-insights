<?php

namespace Kata\Tests\Common\Domain\Criteria;

use Kata\Common\Domain\Criteria\FilterValue;

final class FilterValueMother
{
    public static function create(?string $value = null): FilterValue
    {
        return new FilterValue($value ?? 'fake');
    }
}
