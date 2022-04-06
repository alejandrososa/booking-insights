<?php

namespace Kata\Booking\Domain\Insights\Pipes;

use Kata\Booking\Domain\BookingRequest\BookingRequest;

use function Lambdish\Phunctional\{filter, first, map};

class CleanOverlapsPipe implements Pipe
{
    public function apply(array $data): mixed
    {
        //init date ranges
        $dateRanges = $this->initDateRanges(...$data);

        //find id not overlaps
        $requestIdWithoutOverlap = $this->getRequestIdWithoutOverlaps($dateRanges);

        //filter request by id not overlaps
        $requestWithoutOverlap = [];
        foreach ($requestIdWithoutOverlap as $id) {
            $item = first(filter(fn(BookingRequest $request) => $request->requestId() === $id, [...$data]));
            array_push($requestWithoutOverlap, $item);
        }

        return $requestWithoutOverlap;
    }

    private function getRequestIdWithoutOverlaps(array $requests): array
    {
        $overlap = [];
        foreach ($requests as $date) {
            $dateOnesStart = $date['start'];
            $dateOneEnd = $date['end'];

            foreach ($requests as $date2) {
                $dateTwoStart = $date2['start'];
                $dateTwoEnd = $date2['end'];

                if (($dateOnesStart >= $dateTwoStart && $dateOnesStart <= $dateTwoEnd)
                    || ($dateOneEnd >= $dateTwoStart && $dateOneEnd <= $dateTwoEnd)
                    || ($dateTwoStart >= $dateOnesStart && $dateTwoStart <= $dateOneEnd)
                    || ($dateTwoEnd >= $dateOnesStart && $dateTwoEnd <= $dateOneEnd)) {
                    if (!in_array($date2['request_id'], $overlap)) {
                        $overlap[] = $date2['request_id'];
                    }
                    break;
                }
            }
        }

        return $overlap;
    }

    private function initDateRanges(BookingRequest ...$data): array
    {
        $dateRanges = map(function (BookingRequest $request) {
            $interval = new \DateInterval(sprintf('P%dD', $request->nights()));

            return [
                'request_id' => $request->requestId(),
                'start' => \DateTime::createFromInterface($request->checkIn()),
                'end' => \DateTime::createFromInterface($request->checkIn())->add($interval),
            ];
        }, $data);

        return $dateRanges;
    }
}
