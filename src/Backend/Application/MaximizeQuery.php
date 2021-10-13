<?php
declare(strict_types=1);

namespace Sfl\Backend\Application;


use Sfl\Shared\Infrastructure\Bus\Query;

final class MaximizeQuery implements Query
{
    public function __construct(
        private array $bookings
    )
    {
    }

    /**
     * @return array
     */
    public function bookings(): array
    {
        return $this->bookings;
    }
}