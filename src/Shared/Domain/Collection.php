<?php
declare(strict_types=1);


namespace Sfl\Shared\Domain;


abstract class Collection implements \Iterator, \Countable
{
    protected function __construct(protected array $items)
    {
    }

    public function current()
    {
        return \current($this->items);
    }

    public function next()
    {
        \next($this->items);
    }

    public function key(): float|bool|int|string|null
    {
        return \key($this->items);
    }

    public function valid(): bool
    {
        return \array_key_exists($this->key(), $this->items);
    }

    public function rewind()
    {
        \reset($this->items);
    }

    public function count($mode = COUNT_NORMAL): int
    {
        return \count($this->items, $mode);
    }

    public function walk(callable $func)
    {
        \array_walk($this->items, $func);
    }

    public function filter(callable $func): static
    {
        return new static(\array_values(\array_filter($this->items, $func)));
    }

    public function map(callable $func): static
    {
        return new static(\array_map($func, $this->items));
    }

    public function reduce(callable $func, array $initial = []): array
    {
        return \array_reduce($this->items, $func, $initial);
    }

    public function sort(callable $func): static
    {
        $items = $this->items;
        \usort($items, $func);

        return new static($items);
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function equalTo(object $other): bool
    {
        return static::class === $other::class && $this->items === $other->items;
    }

    final public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items[array_key_first($this->items)] ?? null;
    }

    public function isUnique(): bool
    {
        return !is_null(static::uniqueFN());
    }

    public function isTyped(): bool
    {
        return !is_null(static::typedFN());
    }

    public static function from(array $items): static
    {
        return new static($items);
    }
}