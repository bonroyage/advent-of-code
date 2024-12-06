<?php

namespace MMXXIV\Day6;

use App\Utilities\Coordinate;
use App\Utilities\Direction;

readonly class Movement
{
    public function __construct(
        public ?Coordinate $node,
        public Direction $inDirectionOf,
    ) {
    }

    public function __toString(): string
    {
        return "{$this->node}->{$this->inDirectionOf->name}";
    }

    public function continueStraight(): Movement
    {
        return new Movement(
            node: $this->node->moveInDirection($this->inDirectionOf),
            inDirectionOf: $this->inDirectionOf,
        );
    }

    public function turnRight(): Movement
    {
        $newDirection = $this->inDirectionOf->right90();

        return new self(
            node: $this->node->moveInDirection($newDirection),
            inDirectionOf: $newDirection,
        );
    }

    public function uTurn(): Movement
    {
        return new self(
            node: $this->node,
            inDirectionOf: $this->inDirectionOf->opposite(),
        );
    }

    public function backtrack(): Movement
    {
        return new self(
            node: $this->node->moveInDirection($this->inDirectionOf->opposite()),
            inDirectionOf: $this->inDirectionOf,
        );
    }

    public function offGrid(): Movement
    {
        return new Movement(
            node: null,
            inDirectionOf: $this->inDirectionOf,
        );
    }
}
