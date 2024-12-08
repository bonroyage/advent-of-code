<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXII\Day9\Rope;

return new class ('Rope Bridge') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => explode(' ', $line));
    }

    #[SampleAnswer(13)]
    public function part1(): int
    {
        $rope = new Rope(1);

        foreach ($this->input() as [$direction, $amount]) {
            $rope->move($direction, $amount);
        }

        return $rope->tail()->uniquePositions();
    }

    #[SampleAnswer(1)]
    public function part2(): int
    {
        $rope = new Rope(9);

        foreach ($this->input() as [$direction, $amount]) {
            $rope->move($direction, $amount);
        }

        return $rope->tail()->uniquePositions();
    }
};
