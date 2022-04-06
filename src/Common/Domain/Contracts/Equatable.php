<?php

declare(strict_types=1);

namespace Kata\Common\Domain\Contracts;

interface Equatable
{
    public function equals(Equatable $other): bool;
}
