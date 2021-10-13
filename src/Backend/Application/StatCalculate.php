<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;


use JetBrains\PhpStorm\ArrayShape;
use Sfl\Backend\Domain\BookingCollection;
use function Lambdish\Phunctional\reduce;

final class StatCalculate
{
    public function __construct()
    {
    }

    #[ArrayShape(['min_night' => "mixed", 'max_night' => "mixed", 'avg_night' => "float"])]
    public function __invoke(StatQuery $query): array
    {
        return $this->calculateStat($query->stats());
    }

    #[ArrayShape(['min_night' => "mixed", 'max_night' => "mixed", 'avg_night' => "float"])]
    public function calculateStat(array $stats): array
    {
        $bookingCollection = BookingCollection::fromArray($stats);

        foreach ($bookingCollection as $booking) {
            $booking->calculateStatProfit();
        }
        $statProfit = $this->getMinMaxProfit($bookingCollection);
        $statProfit['avg_night'] = $this->getAvg($bookingCollection);

        return  $statProfit;
    }

    private function getAvg(BookingCollection $collection): float
    {
        $avg = reduce(
            function ($acc, $booking) {
                return $acc + $booking->profit()->value();
            },
            $collection
        );

        return round($avg/$collection->count(), 2);

    }

    #[ArrayShape(['min_night' => "mixed", 'max_night' => "mixed"])]
    private function getMinMaxProfit(BookingCollection $collection): array
    {
        $min = $max = 0;
        foreach ($collection as $key => $booking) {
            $profit = $booking->profit()->value();
            if(0 === $key) {
                $min = $max = $profit;
            } else {
                if($profit < $min) {
                    $min = $profit;
                }

                if($profit > $max){
                    $max = $profit;
                }
            }
        }

        return array(
            'min_night' => $min,
            'max_night' => $max
        );
    }
}