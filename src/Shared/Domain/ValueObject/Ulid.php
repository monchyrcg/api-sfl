<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

class Ulid extends StringValueObject
{
    public static function from(string $value): static
    {
        return new static($value);
    }

    public static function fromTimestamp(int $milliseconds, bool $lowercase = false): static
    {
        return new static(\Ulid\Ulid::fromTimestamp($milliseconds, $lowercase = false));
    }

    public static function generate(bool $lowercase = false): static
    {
        return new static(\Ulid\Ulid::generate($lowercase));
    }
}
