<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXII\Day4\Pair;

return new class('Camp Cleanup') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($pair) => new Pair($pair));
    }

    #[SampleAnswer(2)]
    public function part1(): int
    {
        return $this->input()
            ->map(fn(Pair $pair) => $pair->fullyOverlaps())
            ->filter()
            ->count();
    }

    #[SampleAnswer(4)]
    public function part2(): int
    {
        return $this->input()
            ->map(fn(Pair $pair) => $pair->overlaps())
            ->filter()
            ->count();
    }
};
