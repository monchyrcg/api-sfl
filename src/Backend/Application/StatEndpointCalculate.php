<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;


use Sfl\Backend\Domain\BookingCollection;

final class StatEndpointCalculate
{
    public function __construct()
    {
    }

    public function __invoke(StatEndpointQuery $query): array
    {
        $bookingCollection = BookingCollection::fromArray($query->stats());

        foreach ($bookingCollection as $booking) {
            $booking->calculateProfit();
        }

        $statProfit = $bookingCollection->getMinMaxProfit();
        $statProfit['avg_night'] = $bookingCollection->getAvg();

        return  $statProfit;
    }
}