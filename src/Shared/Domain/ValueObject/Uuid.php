<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid extends StringValueObject
{
    public static function from(string $value): static
    {
        return new static(RamseyUuid::fromString($value)->toString());
    }

    public static function v4(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }
}
