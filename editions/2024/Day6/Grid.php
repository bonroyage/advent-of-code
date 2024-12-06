<?php

namespace MMXXIV\Day6;

use App\Utilities\Coordinate;
use App\Utilities\Direction;
use Closure;
use RuntimeException;

class Grid
{
    public function __construct(
        private array $grid,
    ) {
    }

    public function placeObstacle(Coordinate $node): void
    {
        $this->grid[$node->y][$node->x] = '#';
    }

    public function resetObstacle(Coordinate $node): void
    {
        $this->grid[$node->y][$node->x] = '.';
    }

    public function whileValid(?Closure $callback = null, ?Movement $startAt = null): array
    {
        $lastMove = $startAt?->backtrack() ?? new Movement(
            node: $this->startingPosition(),
            inDirectionOf: Direction::North,
        );

        $movements = [(string) $lastMove => $lastMove];

        while ($move = $this->step($lastMove)) {
            if (isset($movements[(string) $move])) {
                throw new LoopDetectedException();
            }

            $movements[(string) $move] = $move;

            if ($move->node === null) {
                break;
            }

            if ($callback) {
                $callback($move);
            }

            $lastMove = $move;
        }

        return $movements;
    }

    public function isOnGrid(Coordinate $coordinate): bool
    {
        return isset($this->grid[$coordinate->y][$coordinate->x]);
    }

    public function isObstacle(Coordinate $coordinate): bool
    {
        return $this->grid[$coordinate->y][$coordinate->x] === '#';
    }

    private function step(Movement $lastMove): Movement
    {
        $straightOn = $lastMove->continueStraight();

        // If we are out of bounds, we're done
        if (!$this->isOnGrid($straightOn->node)) {
            return $lastMove->offGrid();
        }

        // If we hit an obstacle, we must turn right
        if ($this->isObstacle($straightOn->node)) {
            $turnRight = $lastMove->turnRight();

            if ($this->isObstacle($turnRight->node)) {
                return $lastMove->uTurn();
            }

            return $turnRight;
        }

        return $straightOn;
    }

    private function startingPosition(): Coordinate
    {
        foreach ($this->grid as $y => $row) {
            if (!in_array('^', $row)) {
                continue;
            }

            return new Coordinate(
                x: array_search('^', $row),
                y: $y,
            );
        }

        throw new RuntimeException('Starting position not found');
    }
}
