<?php

namespace Kata\Common\Infrastructure\Bus;

interface BusComponent
{
    public function dispatch(mixed $dto): mixed;
}
