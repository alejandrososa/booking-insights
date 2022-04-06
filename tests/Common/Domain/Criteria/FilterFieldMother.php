<?php

declare(strict_types=1);

namespace Kata\Common\Domain\Criteria;

final class FilterFieldMother
{
    public static function create(?string $fieldName = null): FilterField
    {
        return new FilterField($fieldName ?? 'fake');
    }
}
