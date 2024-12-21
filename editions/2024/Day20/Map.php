<?php

namespace MMXXIV\Day20;

use App\Utilities\AStar\Pathfinding;
use App\Utilities\Coordinate;
use App\Utilities\Direction;
use RuntimeException;

class Map implements Pathfinding
{
    public function __construct(
        public array $grid,
    ) {}

    public function findStart(): StatefulCoordinate
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === 'S') {
                    return new StatefulCoordinate(
                        x: $x,
                        y: $y,
                        direction: Direction::East,
                    );
                }
            }
        }

        throw new RuntimeException('No start');
    }

    public function findEnd(): Coordinate
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === 'E') {
                    return new Coordinate(
                        x: $x,
                        y: $y,
                    );
                }
            }
        }

        throw new RuntimeException('No end');
    }

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

            $neighbours[] = new StatefulCoordinate(
                x: $x,
                y: $y,
                direction: $direction,
            );
        }

        return $neighbours;
    }

    public function isOnGrid(Coordinate $coordinate): bool
    {
        return isset($this->grid[$coordinate->y][$coordinate->x]);
    }

    public function isWall(Coordinate $coordinate): bool
    {
        return $this->grid[$coordinate->y][$coordinate->x] === '#';
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
