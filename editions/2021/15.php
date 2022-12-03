<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day15\Grid;

return new class ('Chiton') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn($line) => str_split($line));
    }


    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());

        $risk = $grid->run();

        return new Part(
            question: 'What is the lowest total risk of any path from the top left to the bottom right?',
            answer: $risk
        );
    }


    public function part2(): Part
    {
        $inputs = $this->input()->all();

        $rows = count($inputs);
        $cols = count($inputs[0]);

        $grid = array_fill(0, $rows * 5, array_fill(0, $cols * 5, null));

        $grid = array_map(function ($row, $y) use ($inputs, $rows, $cols) {
            return array_map(function ($cell, $x) use ($y, $inputs, $rows, $cols) {
                $add = (int)(floor($y / $rows) + floor($x / $cols));
                return ($inputs[$y % $rows][$x % $cols] + $add - 1) % 9 + 1;
            }, $row, array_keys($row));
        }, $grid, array_keys($grid));

        $grid = new Grid($grid);

        $risk = $grid->run();

        return new Part(
            question: 'Using the full map, what is the lowest total risk of any path from the top left to the bottom right?',
            answer: $risk
        );
    }

};
