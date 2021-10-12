<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

abstract class FloatValueObject implements ValueObject
{
    protected function __construct(private float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equalTo(object $other): bool
    {
        return static::class === $other::class && $other->value === $this->value;
    }

    public function isBiggerThan(FloatValueObject $other): bool
    {
        return static::class === $other::class && $this->value > $other->value;
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }

    public static function from(float $value): static
    {
        return new static($value);
    }
}
