<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Calorie Counting') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL . PHP_EOL)
            ->map(fn($elf) => str($elf)->trim()->explode(PHP_EOL)->sum());
    }

    #[SampleAnswer(24_000)]
    public function part1(): int
    {
        return $this->input()
            ->max();
    }

    #[SampleAnswer(45_000)]
    public function part2(): int
    {
        return $this->input()
            ->sortDesc()
            ->take(3)
            ->sum();
    }
};
