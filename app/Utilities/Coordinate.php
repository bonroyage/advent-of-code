<?php

namespace App\Utilities;

class Coordinate
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {
    }

    public function move(int $x = 0, int $y = 0): Coordinate
    {
        return new self(
            x: $this->x + $x,
            y: $this->y + $y,
        );
    }

    public function __toString(): string
    {
        return "{$this->y},{$this->x}";
    }
}
