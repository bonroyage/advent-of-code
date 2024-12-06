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
                ->map(fn(Movement $move) => $move->to)
                ->unique()
                ->filter()
                ->count(),
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->all());

        // Ensure that we cannot place an obstacle on that starting position
        $obstacles = [(string) $grid->start => 0];

        // We're sure that the original grid will not cause a loop, so we don't
        // need to track the visited nodes to prevent a loop here.
        $grid->whileValid(function (Movement $moving) use (&$obstacles, $grid) {
            // If we have already visited the node that we are leaving then we
            // have already determined if placing an obstacle will cause a loop.
            if (isset($obstacles[(string) $moving->from])) {
                return;
            }

            // Create an altered grid with an obstacle placed on the node that
            // we are leaving.
            $alteredGrid = $grid->placeObstacle($moving->from);

            try {
                $alteredGrid->whileValid();
                $obstacles[(string) $moving->from] = 0;
            } catch (LoopDetectedException) {
                $obstacles[(string) $moving->from] = 1;
            }
        });

        return new Part(
            answer: array_count_values($obstacles)[1],
        );
    }
};
