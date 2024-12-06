<?php

use App\Solver\Day;
use App\Solver\Part;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Point of Incidence') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL.PHP_EOL);
    }

    #[SampleAnswer(709)]
    public function part1(): int
    {
        return $this->input()
            ->sum(fn(string $grid): int => $this->checkGrid($grid, false));
    }

    #[SampleAnswer(1_400)]
    public function part2(): int
    {
        return $this->input()
            ->sum(fn(string $grid): int => $this->checkGrid($grid, true));
    }

    private function checkGrid(string $grid, bool $fixSmudge): int
    {
        $horizontalGrid = array_map(
            fn(string $line) => str_split($line),
            explode(PHP_EOL, $grid),
        );

        $horizontal = $this->checkDirection(
            $horizontalGrid,
            $fixSmudge,
        );

        if (isset($horizontal)) {
            return $horizontal * 100;
        }

        $verticalGrid = array_map(
            fn(int $col) => array_column($horizontalGrid, $col),
            array_keys($horizontalGrid[0]),
        );

        return $this->checkDirection(
            $verticalGrid,
            $fixSmudge,
        );
    }

    private function checkDirection(array $grid, bool $fixSmudge): ?int
    {
        for ($i = 1; $i < count($grid); $i++) {
            $canFixSmudge = $fixSmudge;

            /*
             * Increase the distance by which we're looking in the mirror by one
             * and compare those two lines against each other.
             *
             * If one of the two lines doesn't exist, then we've run out on that
             * one side and can assume that the rest matches from there on out.
             */
            for ($j = 0; isset($grid[$i + $j], $grid[$i - $j - 1]); $j++) {
                $distance = $this->distance(
                    $grid[$i + $j],
                    $grid[$i - 1 - $j],
                );

                if ($distance === 1 && $canFixSmudge) {
                    $distance = 0;
                    $canFixSmudge = false;
                }

                /*
                 * There's a difference so the iteration in which we found the
                 * initial two matching rows can't be valid any further.
                 */
                if ($distance > 0) {
                    continue 2;
                }
            }

            /*
             * In the second part, where we can fix a smudge, we will only
             * accept the symmetry if it has been found by fixing a smudge.
             *
             * If we've managed to find the symmetry without fixing any
             * smudge, then we ignore it and find another one.
             */
            if ($canFixSmudge && $fixSmudge) {
                continue;
            }

            return $i;
        }

        return null;
    }

    private function distance(array $array1, array $array2): int
    {
        return array_sum(
            array_map(
                fn($a, $b) => $a === $b ? 0 : 1,
                $array1,
                $array2,
            ),
        );
    }
};
