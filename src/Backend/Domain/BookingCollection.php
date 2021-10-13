<?php
declare(strict_types=1);

namespace Sfl\Backend\Domain;

use Sfl\Shared\Domain\Collection as SflCollection;
use function Lambdish\Phunctional\map;

final class BookingCollection extends SflCollection
{
    public static function fromArray(array $bookings): BookingCollection
    {
        return self::from(self::parseBookings($bookings));
    }

    private static function parseBookings(array $bookings): array
    {
        return map(fn(array $booking) => Booking::fromPrimitives(
            $booking['request_id'],
            $booking['check_in'],
            $booking['nights'],
            $booking['selling_rate'],
            $booking['margin']
        ), $bookings);
    }

     /**
 * @return array
 */public function getItems(): array
{
    return $this->items;
}
}