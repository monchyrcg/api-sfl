<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

abstract class BoolValueObject implements ValueObject
{
    protected function __construct(protected bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }

    final public function jsonSerialize(): bool
    {
        return $this->value;
    }

    public static function from(bool $value): static
    {
        return new static($value);
    }

    public static function true(): static
    {
        return new static(true);
    }

    public static function false(): static
    {
        return new static(false);
    }

    public function equalTo(object $other): bool
    {
        return $this->value === $other->value;
    }
}
