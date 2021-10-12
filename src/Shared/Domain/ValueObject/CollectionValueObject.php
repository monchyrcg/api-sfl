<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

use Sfl\Shared\Domain\Collection as SflCollection;

abstract class CollectionValueObject extends SflCollection implements ValueObject
{
    protected function __construct(protected array $items)
    {
        parent::__construct($this->items);
    }

    public static function from(array $items): static
    {
        if (static::typedFN() && count(array_filter($items, static::typedFN())) !== count($items)) {
            throw new \InvalidArgumentException('Type Mismatch');
        }

        if ($fn = static::uniqueFN()) {
            return new static(array_reduce($items, function ($c, $item) use ($fn) {
                $c[$fn($item)] = $item;

                return $c;
            }, []));
        }

        return new static($items);
    }

    /**
     * @return null|callable Callable that returns a unique hash from object fn($item):string
     */
    public static function uniqueFN(): ?callable
    {
        return null;
    }

    /**
     * @return null|callable Callable that returns a type check from object fn($item):bool
     */
    public static function typedFN(): ?callable
    {
        return null;
    }
}
