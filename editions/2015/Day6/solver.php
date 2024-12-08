<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXV\Day6\Grid;

return new class ('Probably a Fire Hazard') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/^(turn on|turn off|toggle) (\d+),(\d+) through (\d+),(\d+)$/', $line, $matches);

                return [
                    'action' => $matches[1],
                    'start' => [(int) $matches[2], (int) $matches[3]],
                    'end' => [(int) $matches[4], (int) $matches[5]],
                ];
            });
    }

    public function part1(): Part
    {
        $grid = new Grid(1000, 1000);

        foreach ($this->input() as $line) {
            $grid->modify(
                $line['start'],
                $line['end'],
                match ($line['action']) {
                    'turn on' => 1,
                    'turn off' => 0,
                    'toggle' => fn($value) => $value ? 0 : 1,
                },
            );
        }

        return new Part(
            answer: array_count_values($grid->flat())[1] ?? 0,
        );
    }

    public function part2(): Part
    {
        $grid = new Grid(1000, 1000);

        foreach ($this->input() as $line) {
            $grid->modify(
                $line['start'],
                $line['end'],
                match ($line['action']) {
                    'turn on' => fn($value) => $value + 1,
                    'turn off' => fn($value) => max(0, $value - 1),
                    'toggle' => fn($value) => $value + 2,
                },
            );
        }

        return new Part(
            answer: array_sum($grid->flat()),
        );
    }
};
