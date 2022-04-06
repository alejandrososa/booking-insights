<?php

namespace Kata\Tests\Common\Domain\Criteria;

use Kata\Common\Domain\Criteria\Criteria;
use Kata\Common\Domain\Criteria\Filters;
use Kata\Common\Domain\Criteria\Order;

final class CriteriaMother
{
    public static function create(
        Filters $filters,
        Order $order = null,
        int $offset = null,
        int $limit = null
    ): Criteria {
        return new Criteria($filters, $order ?: OrderMother::none(), $offset, $limit);
    }

    public static function empty(): Criteria
    {
        return self::create(FiltersMother::blank(), OrderMother::none());
    }
}
