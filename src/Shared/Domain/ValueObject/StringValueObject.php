<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

abstract class StringValueObject implements ValueObject
{
    protected string $value;

    protected function __construct(\Stringable|string $value)
    {
        $this->value = (string) $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equalTo(object $other): bool
    {
        return static::class === $other::class && $this->value === $other->value;
    }

    final public function jsonSerialize(): string
    {
        return $this->value;
    }

    public static function from(string $value): static
    {
        return new static($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
