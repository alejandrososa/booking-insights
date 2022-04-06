<?php

namespace Kata\Tests\Common\Domain\Criteria;

use Kata\Common\Domain\Criteria\Order;
use Kata\Common\Domain\Criteria\OrderBy;
use Kata\Common\Domain\Criteria\OrderType;

final class OrderMother
{
    public static function create(?OrderBy $orderBy = null, ?OrderType $orderType = null): Order
    {
        return new Order($orderBy ?? OrderByMother::create(), $orderType ?? OrderType::random());
    }

    public static function none(): Order
    {
        return Order::none();
    }
}
