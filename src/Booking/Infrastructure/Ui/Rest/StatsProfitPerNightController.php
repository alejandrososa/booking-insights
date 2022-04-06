<?php

namespace Kata\Booking\Infrastructure\Ui\Rest;

use Kata\Booking\Application\StatsBookingRequest\{CalculateProfitPerNight, ProfitPerNightResponse};
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class StatsProfitPerNightController extends AbstractController
{
    #[Route('/stats', name: 'booking_stats_profit', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $bookings = json_decode($request->getContent(), true);
        $query = new CalculateProfitPerNight($bookings);

        if ($errors = $this->guardHasError($query)) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var ProfitPerNightResponse $response */
        $response = $this->busComponent->dispatch($query);

        return new JsonResponse([
            'avg_night' => $response->avg(),
            'min_night' => $response->min(),
            'max_night' => $response->max(),
        ],
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
