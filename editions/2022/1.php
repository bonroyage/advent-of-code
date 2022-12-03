<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Calorie Counting') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile(PHP_EOL . PHP_EOL)
            ->map(fn($elf) => str($elf)->trim()->explode(PHP_EOL)->sum());
    }


    public function part1(): Part
    {
        $max = $this->input()
            ->max();

        return new Part(
            question: 'Find the Elf carrying the most Calories. How many total Calories is that Elf carrying?',
            answer: $max
        );
    }


    public function part2(): Part
    {
        $sum = $this->input()
            ->sortDesc()
            ->take(3)
            ->sum();

        return new Part(
            question: 'Find the top three Elves carrying the most Calories. How many Calories are those Elves carrying in total?',
            answer: $sum
        );
    }

};
