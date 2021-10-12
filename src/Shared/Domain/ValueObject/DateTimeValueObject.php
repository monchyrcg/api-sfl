<?php

declare(strict_types=1);

namespace Sfl\Shared\Domain\ValueObject;

class DateTimeValueObject extends \DateTimeImmutable implements ValueObject
{
    public function __construct($time = 'now', $timezone = null)
    {
        if (null === $timezone) {
            $timezone = new \DateTimeZone('UTC');
        }

        parent::__construct($time, $timezone);
    }

    final public function jsonSerialize(): string
    {
        return $this->format(\DATE_RFC3339);
    }

    final public static function from(string $str): self
    {
        return new static($str, new \DateTimeZone('UTC'));
    }

    final public static function fromTimestamp(int $timestamp): self
    {
        $dateTime = \DateTimeImmutable::createFromFormat('U', (string) $timestamp);

        return static::from($dateTime->format(\DATE_RFC3339));
    }

    final public static function fromDate(string $date): self
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d 00:00:00', (string) $date);

        return static::from($dateTime->format(\DATE_RFC3339));
    }

    public function equalTo(object $other): bool
    {
        return static::class !== $other::class
            && $this->getTimestamp() === $other->getTimestamp();
    }
}
