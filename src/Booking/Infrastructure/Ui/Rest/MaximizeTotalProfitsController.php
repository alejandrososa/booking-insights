<?php

namespace Kata\Booking\Infrastructure\Ui\Rest;

use Kata\Booking\Application\MaximizeBookingRequest\CalculateBestCombinationProfit;
use Kata\Booking\Application\MaximizeBookingRequest\MaximizeTotalProfitsResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaximizeTotalProfitsController extends AbstractController
{

    #[Route('/maximize', name: 'booking_maximize_profits', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $bookings = json_decode($request->getContent(), true);
        $query = new CalculateBestCombinationProfit($bookings);

        if ($errors = $this->guardHasError($query)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var MaximizeTotalProfitsResponse $response */
        $response = $this->busComponent->dispatch($query);

        return new JsonResponse([
            'request_ids' => $response->requestIds(),
            'total_profit' => $response->totalProfit(),
            'avg_night' => $response->avg(),
            'min_night' => $response->min(),
            'max_night' => $response->max(),
        ],
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
