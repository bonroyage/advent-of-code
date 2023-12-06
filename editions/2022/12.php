<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day12\Grid;

return new class('Hill Climbing Algorithm') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());

        $distance = $grid->run($grid->S);

        return new Part(
            answer: $distance,
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());

        $distance = $grid->run(...$grid->a);

        return new Part(
            answer: $distance,
        );
    }
};
