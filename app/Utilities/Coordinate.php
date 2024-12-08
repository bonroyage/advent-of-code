<?php

namespace App\Utilities;

class Coordinate
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {}

    public function move(int $x = 0, int $y = 0): Coordinate
    {
        return new self(
            x: $this->x + $x,
            y: $this->y + $y,
        );
    }

    public function offset(Coordinate $other): Coordinate
    {
        return new self(
            x: $this->x - $other->x,
            y: $this->y - $other->y,
        );
    }

    public function moveInDirection(Direction $direction): Coordinate
    {
        return $this->move(
            x: $direction->offset()[0],
            y: $direction->offset()[1],
        );
    }

    public function __toString(): string
    {
        return "{$this->y},{$this->x}";
    }
}
