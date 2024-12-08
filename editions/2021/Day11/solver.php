<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day11\Grid;

return new class ('Dumbo Octopus') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $row) => str_split($row));
    }

    #[SampleAnswer(1_656)]
    public function part1(): int
    {
        $grid = new Grid($this->input()->all());
        $flashes = 0;

        for ($i = 1; $i <= 100; $i++) {
            $flashes += $grid->step();
        }

        return $flashes;
    }

    #[SampleAnswer(195)]
    public function part2(): int
    {
        $grid = new Grid($this->input()->all());
        $total = $grid->count();
        $flashed = 0;
        $step = 0;

        while ($flashed !== $total) {
            $step++;
            $flashed = $grid->step();
        }

        return $step;
    }
};
