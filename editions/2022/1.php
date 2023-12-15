<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Calorie Counting') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL.PHP_EOL)
            ->map(fn($elf) => str($elf)->trim()->explode(PHP_EOL)->sum());
    }

    public function part1(): Part
    {
        $max = $this->input()
            ->max();

        return new Part(
            answer: $max,
        );
    }

    public function part2(): Part
    {
        $sum = $this->input()
            ->sortDesc()
            ->take(3)
            ->sum();

        return new Part(
            answer: $sum,
        );
    }
};
