<?php

namespace MMXXIV\Day7;

use Generator;

class Equation
{
    public static bool $withConcatenation = false;

    private static array $operations;

    public static function isPossible(int $target, array $values): bool
    {
        if ($values === []) {
            return $target === 0;
        }

        $value = array_pop($values);

        foreach (self::tryOperations($target, $value) as $newTarget) {
            if (self::isPossible($newTarget, $values)) {
                return true;
            }
        }

        return false;
    }

    private static function tryOperations(int $target, int $right): Generator
    {
        self::$operations ??= [
            self::lhsForAddition(...),
            self::lhsForMultiplication(...),
            self::lhsForConcatenation(...),
        ];

        foreach (self::$operations as $operation) {
            $left = $operation($target, $right);

            if ($left !== null) {
                yield $left;
            }
        }
    }

    private static function lhsForAddition(int $target, int $right): ?int
    {
        $left = $target - $right;

        /*
         * If Left + Right = Target, then Target - Right = Left, but Left must be
         * a positive number. So if Left is less than 0, then it is by
         * definition not possible to go this route.
         */
        if ($left < 0) {
            return null;
        }

        return $left;
    }

    private static function lhsForMultiplication(int $target, int $right): ?int
    {
        /*
         * If Left * Right = Target, then Target / Right = Left, but Left must be
         * a whole number. So if the division has a remainder, then it is by
         * definition not possible to go this route.
         */
        if ($target % $right !== 0) {
            return null;
        }

        return $target / $right;
    }

    private static function lhsForConcatenation(int $target, int $right): ?int
    {
        /*
         * Concatenation is not supported in this scenario.
         */
        if (!self::$withConcatenation) {
            return null;
        }

        /*
         * If Left and Right glued together form Target, then Target must end with
         * Right and still have a number left over. So the Right cannot be equal
         * to or greater than the Target.
         */
        if ($right >= $target) {
            return null;
        }

        $multiplier = pow(10, ceil(log10($right + 1)));

        /*
         * If Left and Right glued together form Target, then Target must end with
         * Right. So if the Target modulo the multiplier is not equal to the
         * Right, then we know that Target does not end with Right, and we can
         * stop checking this route.
         */
        if ($target % $multiplier !== $right) {
            return null;
        }

        return ($target - $right) / $multiplier;
    }
}
