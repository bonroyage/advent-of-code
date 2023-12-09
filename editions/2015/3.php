<?php

use App\Solver\Day;
use App\Solver\Part;

return new class('Perfectly Spherical Houses in a Vacuum') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): array
    {
        return str_split($this->getFile());
    }

    public function part1(): Part
    {
        $position = [0, 0];
        $visited = [
            '0,0' => 2,
        ];

        foreach ($this->input() as $i => $direction) {
            $position = $this->move($direction, $position);

            $visited["{$position[0]},{$position[1]}"] ??= 0;
            $visited["{$position[0]},{$position[1]}"]++;
        }

        return new Part(
            answer: count($visited),
        );
    }

    public function part2(): Part
    {
        $santa = [0, 0];
        $robosanta = [0, 0];

        $visited = [
            '0,0' => 2,
        ];

        foreach ($this->input() as $i => $direction) {
            if ($i % 2 === 0) {
                $position = $santa = $this->move($direction, $santa);
            } else {
                $position = $robosanta = $this->move($direction, $robosanta);
            }

            $visited["{$position[0]},{$position[1]}"] ??= 0;
            $visited["{$position[0]},{$position[1]}"]++;
        }

        return new Part(
            answer: count($visited),
        );
    }

    private function move(string $direction, array $position): array
    {
        return match ($direction) {
            '>' => [$position[0] + 1, $position[1]],
            '<' => [$position[0] - 1, $position[1]],
            '^' => [$position[0], $position[1] - 1],
            'v' => [$position[0], $position[1] + 1],
        };
    }
};
