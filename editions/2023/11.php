<?php

use App\Solver\Day;
use App\Solver\Part;
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

    public function part1(): Part
    {
        $grid = $this->input();

        return new Part(
            answer: $grid->shortestDistanceWithExpansion(2),
        );
    }

    public function part2(): Part
    {
        $grid = $this->input();

        return new Part(
            answer: $grid->shortestDistanceWithExpansion(1_000_000),
        );
    }
};
