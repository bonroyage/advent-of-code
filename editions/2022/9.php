<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day9\Rope;

return new class('Rope Bridge') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => explode(' ', $line));
    }

    public function part1(): Part
    {
        $rope = new Rope(1);

        foreach ($this->input() as [$direction, $amount]) {
            $rope->move($direction, $amount);
        }

        return new Part(
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
            answer: $rope->tail()->uniquePositions(),
        );
    }
};
