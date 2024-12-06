<?php

namespace MMXXIV\Day6;

use App\Utilities\Coordinate;
use Closure;
use RuntimeException;

class Grid
{
    public readonly Coordinate $start;

    public function __construct(
        public readonly array $grid,
    ) {
        $this->start = $this->startingPosition();
    }

    public function placeObstacle(Coordinate $atPosition): self
    {
        $grid = $this->grid;
        $grid[$atPosition->y][$atPosition->x] = '#';

        return new self($grid);
    }

    public function whileValid(?Closure $callback = null): array
    {
        $lastMove = new Movement(
            from: null,
            to: $this->start,
            inDirectionOf: 'n',
        );

        $movements = [$lastMove];

        while ($move = $this->step($lastMove)) {
            if (in_array($move, $movements)) {
                throw new LoopDetectedException($movements, $move);
            }

            $movements[] = $move;

            if ($callback) {
                $callback($move);
            }

            if ($move->to === null) {
                break;
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
        if (!$this->isOnGrid($straightOn->to)) {
            return $lastMove->offGrid();
        }

        // If we hit an obstacle, we must turn right
        if ($this->isObstacle($straightOn->to)) {
            $nextMove = $lastMove->turnRight();

            if ($this->isObstacle($nextMove->to)) {
                return $lastMove->uTurn();
            }

            return $nextMove;
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
