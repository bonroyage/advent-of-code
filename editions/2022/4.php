<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day4\Pair;

return new class('Camp Cleanup') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($pair) => new Pair($pair));
    }

    public function part1(): Part
    {
        $overlaps = $this->input()
            ->map(fn(Pair $pair) => $pair->fullyOverlaps())
            ->filter()
            ->count();

        return new Part(
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
            answer: $overlaps,
        );
    }
};
