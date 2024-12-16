<?php

namespace MMXXIV\Day16;

use App\Utilities\AStar\Pathfinding;
use App\Utilities\Coordinate;
use App\Utilities\Direction;
use InvalidArgumentException;
use RuntimeException;

class Map implements Pathfinding
{
    public function __construct(
        private readonly array $grid,
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
        if (!$for instanceof StatefulCoordinate) {
            throw new InvalidArgumentException('Expected State');
        }

        $neighbours = [];

        foreach (Direction::Orthogonal as $direction) {
            if ($direction->opposite() === $for->direction) {
                continue;
            }

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

    public function costToMove(Coordinate $from, Coordinate $to): int
    {
        /** @var StatefulCoordinate $from */
        /** @var StatefulCoordinate $to */
        return 1
            + ($from->direction !== $to->direction ? 1000 : 0);
    }

    public function distance(Coordinate $from, Coordinate $to): int
    {
        return 1;
    }

    public function goalReached(Coordinate $at, Coordinate $goal): bool
    {
        /** @var StatefulCoordinate $at */
        return $at->x === $goal->x
            && $at->y === $goal->y;
    }
}
