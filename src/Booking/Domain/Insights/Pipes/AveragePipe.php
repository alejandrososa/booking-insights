<?php

namespace Kata\Booking\Domain\Insights\Pipes;

class AveragePipe implements Pipe
{
    public function apply(array $data): mixed
    {
        $avg = round(array_sum($data) / count($data), 2, PHP_ROUND_HALF_DOWN);

        return (float)number_format($avg, 2);
    }
}
