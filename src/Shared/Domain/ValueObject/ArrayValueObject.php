<?php

namespace Sfl\Shared\Domain\ValueObject;

use InvalidArgumentException;

class ArrayValueObject implements ValueObject
{
    protected array $value;

    public function __construct(?array $value = [])
    {
        $this->value = $value;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }

    public static function empty(): static
    {
        return new static();
    }

    public static function from(array $items): static
    {
        return new static($items);
    }

    public function equalTo(object $other): bool
    {
        return static::class !== $other::class && $this->value == $other->value;
    }
}
