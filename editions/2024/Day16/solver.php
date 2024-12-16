<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\AStar\AStar;
use Illuminate\Support\Collection;
use MMXXIV\Day16\Map;

return new class ('Reindeer Maze') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(7036)]
    public function part1(): int
    {
        $grid = $this->input()->all();

        $map = new Map(
            grid: $grid,
        );

        $aStar = new AStar(
            map: $map
        );

        $shortestPath = $aStar->shortestPath(
            from: $map->findStart(),
            to: $map->findEnd(),
        );

        return $shortestPath?->gScore;
    }

    #[SampleAnswer(45)]
    public function part2(): int
    {
        $grid = $this->input()->all();

        $map = new Map(
            grid: $grid,
        );

        $aStar = new AStar(
            map: $map
        );

        $shortestPaths = $aStar->shortestPaths(
            from: $map->findStart(),
            to: $map->findEnd(),
        );

        $seats = [];

        foreach ($shortestPaths as $shortestPath) {
            $seats["{$shortestPath->coordinate->x},{$shortestPath->coordinate->y}"] = true;
            $parent = $shortestPath->parent;

            while ($parent !== null) {
                $seats["{$parent->coordinate->x},{$parent->coordinate->y}"] = true;
                $parent = $parent->parent;
            }
        }

        return count($seats);
    }
};
