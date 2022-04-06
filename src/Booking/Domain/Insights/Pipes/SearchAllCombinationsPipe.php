<?php

namespace Kata\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\BookingRequest\BookingRequest;

class SearchAllCombinationsPipe implements Pipe
{
    public function apply(array $data): mixed
    {
        //find all combinations where each product is either selected or not - true or false
        $combos = $this->generateCombinations([true, false], count($data));

        return $this->groupCombinationsAndCalculateProfit($combos, $data);
    }

    private function generateCombinations($values, $count = 0): array
    {
        // Figure out how many combinations are possible:
        $comboCount = pow(count($values), $count);
        $combinations = [];

        // Iterate and add to array
        for ($i = 0; $i < $comboCount; $i++) {
            $combinations[] = $this->getCombination($values, $count, $i);
        }

        return $combinations;
    }

    private function getCombination($values, $count, $index): array
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            // Figure out where in the array to start from, given the external state and the internal loop state
            $pos = $index % count($values);

            $result[] = $values[$pos];
            $index = ($index - $pos) / count($values);
        }

        return $result;
    }

    private function groupCombinationsAndCalculateProfit(array $combinations, array $requests): array
    {
        $results = [];
        foreach ($combinations as $combination) {
            //loop through each combination and get a result
            $profit = 0;
            $items = [];
            foreach ($combination as $index => $requestIsEnable) {
                //loop through the array of true/false values determining if an item is on or off
                //if on, add item to result
                if ($requestIsEnable && $requests[$index] instanceof BookingRequest) {
                    $profit += $this->calculateProfit($requests[$index]);
                    $items[] = $requests[$index];
                }
            }

            $results[] = [
                'items' => $items,
                'profit' => $profit,
            ];
        }

        return $results;
    }

    private function calculateProfit(BookingRequest $request): float
    {
        return (float)$request->sellingRate() * (float)($request->margin() / 100);
    }
}
