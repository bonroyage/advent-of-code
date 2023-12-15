<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXIII\Day3\Grid;

return new class('Gear Ratios') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());

        $gearRatio = collect($grid->run())
            ->flatMap(fn(array $symbol) => $symbol['numbers'])
            ->sum();

        return new Part(
            answer: $gearRatio,
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());

        $gearRatio = collect($grid->run())
            ->filter(fn(array $symbol) => $symbol['char'] === '*' && count($symbol['numbers']) === 2)
            ->map(fn(array $symbol) => array_product($symbol['numbers']))
            ->sum();

        return new Part(
            answer: $gearRatio,
        );
    }
};
