<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day9\Rope;

return new class ('Rope Bridge') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn($line) => explode(' ', $line));
    }


    public function part1(): Part
    {
        $rope = new Rope(1);

        foreach ($this->input() as [$direction, $amount]) {
            $rope->move($direction, $amount);
        }

        return new Part(
            question: 'Simulate your complete hypothetical series of motions. How many positions does the tail of the rope visit at least once?',
            answer: $rope->tail()->uniquePositions(),
        );
    }


    public function part2(): Part
    {
        $rope = new Rope(9);

        foreach ($this->input() as [$direction, $amount]) {
            $rope->move($direction, $amount);
        }

        return new Part(
            question: 'Simulate your complete series of motions on a larger rope with ten knots. How many positions does the tail of the rope visit at least once?',
            answer: $rope->tail()->uniquePositions(),
        );
    }

};
