<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

class DateTimeRangeValueObject implements ValueObject
{
    private function __construct(private DateTimeValueObject $start, private DateTimeValueObject $end)
    {
    }

    public function start(): DateTimeValueObject
    {
        return $this->start;
    }

    public function end(): DateTimeValueObject
    {
        return $this->end;
    }

    public function isInDate(): bool
    {
        $now = new DateTimeValueObject();

        return $now >= $this->start && $now <= $this->end;
    }

    public function isExpired(): bool
    {
        return (new DateTimeValueObject()) > $this->end;
    }

    public function isNotStarted(): bool
    {
        return (new DateTimeValueObject()) < $this->start;
    }

    public function equalTo(object $other): bool
    {
        return static::class !== $other::class
            && $this->start->getTimestamp() === $other->start->getTimestamp()
            && $this->end->getTimestamp() === $other->end->getTimestamp();
    }

    public function jsonSerialize(): array
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
        ];
    }

    public static function from(DateTimeValueObject $start, DateTimeValueObject $end): DateTimeRangeValueObject
    {
        if ($start >= $end) {
            throw new \InvalidArgumentException('Start date can not be greater than the end date.');
        }

        return new self($start, $end);
    }
}
