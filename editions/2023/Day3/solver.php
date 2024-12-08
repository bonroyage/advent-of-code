<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXIII\Day3\Grid;

return new class ('Gear Ratios') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    #[SampleAnswer(4_361)]
    public function part1(): int
    {
        $grid = new Grid($this->input()->all());

        return collect($grid->run())
            ->flatMap(fn(array $symbol) => $symbol['numbers'])
            ->sum();
    }

    #[SampleAnswer(467_835)]
    public function part2(): int
    {
        $grid = new Grid($this->input()->all());

        return collect($grid->run())
            ->filter(fn(array $symbol) => $symbol['char'] === '*' && count($symbol['numbers']) === 2)
            ->map(fn(array $symbol) => array_product($symbol['numbers']))
            ->sum();
    }
};
