<?php

declare(strict_types=1);

namespace Sfl\Shared\Application\Bus;

interface CommandBus
{
    public function dispatch(...$commands): void;
}