<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXIV\Day6\Grid;
use MMXXIV\Day6\LoopDetectedException;
use MMXXIV\Day6\Movement;

return new class('Guard Gallivant') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->input()->all());

        $movements = $grid->whileValid();

        return new Part(
            answer: collect($movements)
                ->map(fn(Movement $move) => $move->node)
                ->unique()
                ->filter()
                ->count(),
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());

        $obstacles = [];

        // We're sure that the original grid will not cause a loop, so we don't
        // need to track the visited nodes to prevent a loop here.
        $grid->whileValid(static function (Movement $moving) use (&$obstacles, $grid) {
            // If we have already visited the node that we are leaving then we
            // have already determined if placing an obstacle will cause a loop.
            if (isset($obstacles[(string) $moving->node])) {
                return;
            }

            $grid->placeObstacle($moving->node);

            try {
                $grid->whileValid(startAt: $moving);
                $obstacles[(string) $moving->node] = 0;
            } catch (LoopDetectedException) {
                $obstacles[(string) $moving->node] = 1;
            }

            $grid->resetObstacle($moving->node);
        });

        return new Part(
            answer: array_count_values($obstacles)[1],
        );
    }
};
