<?php

namespace Kata\Booking\Domain\Insights\Pipes;

interface Pipe
{
    public function apply(array $data): mixed;
}
