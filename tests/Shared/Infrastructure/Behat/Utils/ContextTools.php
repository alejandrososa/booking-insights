<?php

declare(strict_types=1);

namespace Kata\Tests\Shared\Infrastructure\Behat\Utils;

use Assert\Assertion;
use DateTimeImmutable;
use SebastianBergmann\Diff\Differ;
use Swaggest\JsonDiff\JsonDiff;

class ContextTools
{
    // The dates are <date:str-date[,format]>
    private const DATE_PATTERN = '/<date:([^,>]+)(,(.+))?>/';

    public static function replaceDynamicFields(string $searchString): string
    {
        if (preg_match_all(self::DATE_PATTERN, $searchString, $matches) > 0) {
            $fullDates = array_unique($matches[0]);
            $dates = $matches[1];
            $formats = $matches[3];
            $formattedDates = [];
            foreach (array_keys($fullDates) as $key) {
                $formattedDates[] = (new DateTimeImmutable($dates[$key]))->format($formats[$key] ?: 'Y-m-d');
            }

            $searchString = str_replace($fullDates, $formattedDates, $searchString);
        }

        return $searchString;
    }

    public static function jsonComparatorAssertion(
        string $expectedJson,
        string $actualJson,
        bool $canonically = false
    ): void {
        $expectedJson = self::jsonClean(self::replaceDynamicFields($expectedJson));
        $actualJson = self::jsonClean($actualJson);
        if ($canonically) {
            $options = JsonDiff::REARRANGE_ARRAYS | JsonDiff::TOLERATE_ASSOCIATIVE_ARRAYS | JsonDiff::STOP_ON_DIFF;
            $expectedJson = self::jsonEncode(
                (new JsonDiff([], self::jsonDecode($expectedJson), $options))->getRearranged()
            );
            $actualJson = self::jsonEncode(
                (new JsonDiff([], self::jsonDecode($actualJson), $options))->getRearranged()
            );
        }
        $expectedLines = self::toLines($expectedJson);
        $actuallines = self::toLines($actualJson);
        $diffText = (new Differ('--- Expected' . PHP_EOL . '+++ Response' . PHP_EOL))
            ->diff($expectedLines, $actuallines);
        $escapedDiffTextForSprintf = \str_replace('%', '%%', $diffText);

        Assertion::eq($expectedLines, $actuallines, $escapedDiffTextForSprintf);
    }

    private static function toLines(string $text): array
    {
        return array_map(
            static fn ($line) => $line . PHP_EOL,
            explode(PHP_EOL, $text)
        );
    }

    public static function jsonClean(string $expectedJson): string
    {
        return self::jsonEncode(self::jsonDecode($expectedJson));
    }

    public static function jsonEncode($jsonString): string
    {
        return json_encode($jsonString, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public static function jsonDecode(string $jsonString): ?array
    {
        return json_decode($jsonString, true, JSON_THROW_ON_ERROR);
    }
}
