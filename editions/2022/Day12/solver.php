<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXII\Day12\Grid;

return new class ('Hill Climbing Algorithm') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    #[SampleAnswer(31)]
    public function part1(): int
    {
        $grid = new Grid($this->input()->all());

        return $grid->run($grid->S);
    }

    #[SampleAnswer(29)]
    public function part2(): int
    {
        $grid = new Grid($this->input()->all());

        return $grid->run(...$grid->a);
    }
};
