<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain;

interface AggregateRoot
{
    public function pullEvents(): iterable;
}
