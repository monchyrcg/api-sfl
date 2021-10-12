<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\Annotation;

#[\Attribute]
class DomainEvent
{
    public function __construct(private string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}
