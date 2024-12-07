<?php

namespace App\Solver;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Closure;

class Measure
{
    /** @return array{0: mixed, 1: CarbonInterval} */
    public static function block(Closure $callback): array
    {
        $start = CarbonImmutable::now();

        $value = $callback();

        return [$value, CarbonImmutable::now()->diffAsCarbonInterval($start)];
    }

    public static function format(CarbonInterval $interval): string
    {
        if ($interval->totalSeconds < 0.001) {
            return $interval->microseconds.' μs';
        }

        if ($interval->totalSeconds < 1) {
            return $interval->milliseconds.' ms';
        }

        if ($interval->minutes < 1) {
            return $interval->seconds.' s '.$interval->milliseconds.' ms';
        }

        return $interval->minutes.' m '.$interval->seconds.' s';
    }
}
