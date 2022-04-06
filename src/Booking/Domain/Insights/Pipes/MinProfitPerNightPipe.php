<?php

namespace Kata\Booking\Domain\Insights\Pipes;

class MinProfitPerNightPipe implements Pipe
{
    public function apply(array $data): mixed
    {
        return min($data);
    }
}
