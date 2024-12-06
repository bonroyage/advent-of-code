<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use MMXXIII\Day11\Grid;

return new class('Cosmic Expansion') extends Day
{
    private function input(): Grid
    {
        return new Grid(
            $this->getFileLines()
                ->map(fn(string $line) => str_split($line))->toArray(),
        );
    }

    #[SampleAnswer(374)]
    public function part1(): int
    {
        $grid = $this->input();

        return $grid->shortestDistanceWithExpansion(2);
    }

    public function part2(): int
    {
        $grid = $this->input();

        return $grid->shortestDistanceWithExpansion(1_000_000);
    }
};
