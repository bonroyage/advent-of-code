<?php

namespace MMXXIV\Day10;

use App\Utilities\AStar\Pathfinding;
use App\Utilities\Coordinate;
use App\Utilities\Direction;

readonly class Map implements Pathfinding
{
    public function __construct(
        private array $grid,
    ) {}

    public function neighbours(Coordinate $for): array
    {
        $neighbours = [];
        $elevation = $this->grid[$for->y][$for->x]->elevation;

        foreach (Direction::Orthogonal as $direction) {
            [$x, $y] = $direction->offset();

            $x += $for->x;
            $y += $for->y;

            if (!isset($this->grid[$y][$x])) {
                continue;
            }

            if ($this->grid[$y][$x]->elevation <= $elevation || $this->grid[$y][$x]->elevation > ($elevation + 1)) {
                continue;
            }

            $neighbours[] = new ElevatedCoordinate(
                x: $x,
                y: $y,
                elevation: $this->grid[$y][$x]->elevation,
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
