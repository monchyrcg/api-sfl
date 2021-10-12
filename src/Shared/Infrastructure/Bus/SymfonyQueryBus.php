<?php

declare(strict_types=1);

namespace Sfl\Shared\Infrastructure\Bus;

use Sfl\Shared\Application\Bus\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function ask($query)
    {
        $stamp =  $this->bus->dispatch($query)->last(HandledStamp::class);

        return $stamp->getResult();
    }
}
