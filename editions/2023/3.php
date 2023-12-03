<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXIII\Day3\Grid;

return new class ('Gear Ratios') extends Day
{

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn ($line) => str_split($line));;
    }


    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());

        $gearRatio = collect($grid->run())
            ->flatMap(fn (array $symbol) => $symbol['numbers'])
            ->sum();

        return new Part(
            question: 'What is the sum of all of the part numbers in the engine schematic?',
            answer: $gearRatio,
        );
    }


    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());

        $gearRatio = collect($grid->run())
            ->filter(fn (array $symbol) => $symbol['char'] === '*' && count($symbol['numbers']) === 2)
            ->map(fn (array $symbol) => array_product($symbol['numbers']))
            ->sum();

        return new Part(
            question: 'What is the sum of all of the gear ratios in your engine schematic?',
            answer: $gearRatio,
        );
    }

};
