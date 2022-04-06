<?php

namespace Kata\Tests\Booking\Domain\Insights\Calculator;

use Kata\Booking\Domain\Insights\Calculator\ProfitPerNightCalculator;
use Kata\Booking\Domain\Insights\Pipes\AveragePipe;
use Kata\Booking\Domain\Insights\Pipes\CalculateProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\MaxProfitPerNightPipe;
use Kata\Booking\Domain\Insights\Pipes\MinProfitPerNightPipe;

class ProfitPerNightCalculatorMother
{
    public static function create(): ProfitPerNightCalculator {
        return new ProfitPerNightCalculator(
            averagePipe: new AveragePipe(),
            minProfitPerNightPipe: new MinProfitPerNightPipe(),
            maxProfitPerNightPipe: new MaxProfitPerNightPipe(),
            calculateProfitPerNightPipe: new CalculateProfitPerNightPipe(),
        );
    }
}
