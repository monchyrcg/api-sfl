<?php

declare(strict_types=1);

namespace Sfl\Shared\Infrastructure\Bus;

use Sfl\Shared\Application\Bus\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(...$commands): void
    {
        foreach ($commands as $command) {
            $this->bus->dispatch($command);
        }
    }
}
