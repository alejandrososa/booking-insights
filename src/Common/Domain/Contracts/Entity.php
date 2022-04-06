<?php

namespace Kata\Common\Domain\Contracts;

interface Entity extends Equatable
{
    public function toArray(): array;
}
