<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

interface ValueObject extends \JsonSerializable
{
    public function equalTo(object $other): bool;
}
