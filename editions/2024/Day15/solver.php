<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\Direction;
use MMXXIV\Day15\Grid;

return new class ('Warehouse Woes') extends Day {
    private function input(): array
    {
        [$grid, $instructions] = $this->getFileLines(PHP_EOL . PHP_EOL);

        $grid = str($grid)
            ->explode(PHP_EOL)
            ->map(fn($line) => str_split($line))
            ->values()
            ->all();

        $instructions = str($instructions)
            ->explode(PHP_EOL)
            ->flatMap(fn($line) => str_split($line))
            ->map(fn(string $direction) => match ($direction) {
                '^' => Direction::Up,
                '>' => Direction::Right,
                'v' => Direction::Down,
                '<' => Direction::Left,
            })
            ->all();

        return [$grid, $instructions];
    }

    #[SampleAnswer(10092)]
    public function part1(): int
    {
        [$grid, $instructions] = $this->input();

        $grid = new Grid($grid);

        $coordinate = $grid->findStart();

        foreach ($instructions as $instruction) {
            $coordinate = $grid->move($coordinate, $instruction);
        }

        $sum = 0;

        foreach ($grid->boxes() as $box) {
            $sum += $box->y * 100 + $box->x;
        }

        return $sum;
    }

    #[SampleAnswer(9021)]
    public function part2(): int
    {
        [$grid, $instructions] = $this->input();

        $grid = new Grid($grid);
        $grid->expand();

        $coordinate = $grid->findStart();

        foreach ($instructions as $instruction) {
            $coordinate = $grid->move2($coordinate, $instruction);
        }

        $sum = 0;

        foreach ($grid->boxes() as $box) {
            $sum += $box->y * 100 + $box->x;
        }

        return $sum;
    }
};
