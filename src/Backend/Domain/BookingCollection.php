<?php
declare(strict_types=1);

namespace Sfl\Backend\Domain;

use Sfl\Shared\Domain\Collection as SflCollection;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

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

    public function getAvg(): float
    {
        $avg = reduce(
            function ($acc, $booking) {
                return $acc + $booking->profit();
            },
            $this
        );

        return round($avg/$this->count(), 2);

    }

    public function getMinMaxProfit(): array
    {
        $min = $max = 0;
        foreach ($this as $key => $booking) {
            $profit = $booking->profit();
            if(0 === $key) {
                $min = $max = $profit;
            }

            if($profit < $min) {
                $min = $profit;
            }

            if($profit > $max){
                $max = $profit;
            }
        }
        return array(
            'min_night' => $min,
            'max_night' => $max
        );
    }
}