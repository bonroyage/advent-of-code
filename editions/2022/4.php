<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day4\Pair;

return new class('Camp Cleanup') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn($pair) => new Pair($pair));
    }

    public function part1(): Part
    {
        $overlaps = $this->input()
            ->map(fn(Pair $pair) => $pair->fullyOverlaps())
            ->filter()
            ->count();

        return new Part(
            question: 'In how many assignment pairs does one range fully contain the other?',
            answer: $overlaps,
        );
    }

    public function part2(): Part
    {
        $overlaps = $this->input()
            ->map(fn(Pair $pair) => $pair->overlaps())
            ->filter()
            ->count();

        return new Part(
            question: 'In how many assignment pairs do the ranges overlap?',
            answer: $overlaps,
        );
    }
};
