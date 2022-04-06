<?php

namespace Kata\Booking\Domain\Insights\Pipes;

class MaxProfitPerNightPipe implements Pipe
{
    public function apply(array $data): mixed
    {
        return max($data);
    }
}
