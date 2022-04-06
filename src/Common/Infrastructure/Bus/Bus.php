<?php

namespace Kata\Common\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class Bus implements BusComponent
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(mixed $dto): mixed
    {
        $envelope = $this->bus->dispatch($dto);
        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}
