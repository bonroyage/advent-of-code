<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day11\Grid;

return new class('Dumbo Octopus') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $row) => str_split($row));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());
        $flashes = 0;

        for ($i = 1; $i <= 100; $i++) {
            $flashes += $grid->step();
        }

        return new Part(
            answer: $flashes,
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());
        $total = $grid->count();
        $flashed = 0;
        $step = 0;

        while ($flashed !== $total) {
            $step++;
            $flashed = $grid->step();
        }

        return new Part(
            answer: $step,
        );
    }
};
