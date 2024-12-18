<?php

namespace MMXXIV\Day18;

use App\Utilities\AStar\Pathfinding;
use App\Utilities\Coordinate;
use App\Utilities\Direction;

class Map implements Pathfinding
{
    public function __construct(
        public array $grid,
    ) {}

    public function neighbours(Coordinate $for): array
    {
        $neighbours = [];

        foreach (Direction::Orthogonal as $direction) {
            [$x, $y] = $direction->offset();

            $x += $for->x;
            $y += $for->y;

            if (!isset($this->grid[$y][$x])) {
                continue;
            }

            if ($this->grid[$y][$x] === '#') {
                continue;
            }

            $neighbours[] = new Coordinate(
                x: $x,
                y: $y,
            );
        }

        return $neighbours;
    }

    public function costToMove(Coordinate $from, Coordinate $to): int
    {
        return 1;
    }

    public function distance(Coordinate $from, Coordinate $to): int
    {
        return 1;
    }

    public function goalReached(Coordinate $at, Coordinate $goal): bool
    {
        return $at->x === $goal->x
            && $at->y === $goal->y;
    }
}
