<?php
declare(strict_types=1);


namespace Sfl\Backend\Application;

use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use Sfl\Backend\Domain\BookingCollection;
use function Lambdish\Phunctional\map;

final class MaximizeCalculate
{
    public BookingCollection $bookingCollection;

    public function __construct()
    {
    }

    #[ArrayShape(['total_profit' => "mixed", 'request_ids' => "array", 'min_night' => "mixed", 'max_night' => "mixed", 'avg_night' => "mixed"])]
    public function __invoke(MaximizeQuery $query): array
    {
        $this->bookingCollection = BookingCollection::fromArray($query->bookings());

        foreach ($this->bookingCollection as $booking) {
            $booking->calculateMaximizeProfit();
        }

        $combinations = $this->getCombinations($query->bookings());

        $result = $this->getResults($combinations);

        $children = $result[array_key_first($result)]['children'];
        $bestCombinations = is_array($children) ? $children : array($result[array_key_first($result)]['children']);
        array_push($bestCombinations, array_key_first($result));

        $bestCombinationsArray = map(
                fn(int $position ) =>
                    $this->bookingCollection->position($position)->jsonSerialize()
        , $bestCombinations);

        $statsCombinations = (new StatCalculate())->calculateStat($bestCombinationsArray);
        return array(
            'total_profit' => $result[array_key_first($result)]['total'],
            'request_ids' => array_column($bestCombinationsArray, 'request_id'),
            'min_night' => $statsCombinations['min_night'],
            'max_night' => $statsCombinations['max_night'],
            'avg_night' => $statsCombinations['avg_night'],
        );
    }

    private function getCombinations(array $bookings): array
    {
        // sort by date
        usort($bookings, function($a, $b)
        {
            if ($a['check_in'] == $b['check_in']) {
                return 0;
            }
            return ($a['check_in'] < $b['check_in']) ? -1 : 1;
        });

        // get startDates
        $startDates = array_column($bookings, 'check_in');

        // calculate checkout dates
        array_walk($bookings, function (&$a) {
            $a['check_out'] = Carbon::create($a['check_in'])->add($a['nights'], 'days')->format('Y-m-d');
            return $a;
        });
        //get finishDates
        $finishDates = array_column($bookings, 'check_out');

        $combinations = null;
        foreach ($finishDates as $key => $finish){
            $combinations[$key] = array_filter($startDates, function($start) use ($finish){
                return $start > $finish;
            });

        }

        return $this->parseCombinations($combinations);
    }

    private function parseCombinations(array $filters): array
    {
        $combinations = [];
        foreach($filters as $key => $filter) {
            if(1 === count($filter)) {
               $combinations[$key] = array_key_first($filter);

            } else {
                $combinations[$key] = array_keys($filter);
            }
        }

        return $combinations;
    }

    private function getChildren(array $all, array $children): string
    {
        $chi  = '';
        foreach ($children as $child) {
            $var = $all[$child];

            if(is_numeric($var)) {
                $chi .= $var.',';
            } elseif(is_array($var) && !empty($var)) {
                $chi .=$this->getChildren($all, $var);
            }
        }

        return $chi;
    }

    private function getResults(array $combinations): array
    {
        $results = [];
        foreach ($combinations as $key => $combination){
            $keyProfit = $this->bookingCollection->position($key)->profit()->value();

            if(is_numeric($combination)) {
                if(0 === count($combinations[$combination])) {
                    $results[$key]['total'] = $keyProfit+
                        $this->bookingCollection->position($combination)->profit()->value();
                    $results[$key]['children'] = $combination;
                }
            } elseif(is_array($combination) && !empty($combination)) {
                $children = implode('', array_unique(explode(',', $this->getChildren($combinations, $combination))));
                $custom = array_unique(array_merge($combination, explode(',', $children)));
                $sum = $keyProfit;

                foreach ($custom as $c){
                    $sum += $this->bookingCollection->position($c)->profit()->value();
                }
                $results[$key]['total'] = $sum;
                $results[$key]['children'] = $custom;
            }
        }

        $max = 0;
        $key = 0;


        foreach ($results as $keyA => $result) {
            if($result > $max){
                $max = $result;
                $key = $keyA;
            }
        }

        return array($key => $max);
    }
}