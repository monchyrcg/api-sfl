<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

abstract class IntValueObject implements ValueObject
{
    protected function __construct(protected int $value = 0)
    {
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equalTo(object $other): bool
    {
        return static::class === \get_class($other) && $this->value === $other->value;
    }

    public function isBiggerThan(IntValueObject $other): bool
    {
        return static::class === \get_class($other) && $this->value > $other->value;
    }

    final public function jsonSerialize(): int
    {
        return $this->value;
    }

    public static function from(int $value): static
    {
        return new static($value);
    }
}
