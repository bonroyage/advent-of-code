<?php

namespace MMXXIV\Day12;

use App\Utilities\Coordinate;

class Garden
{
    public function __construct(
        private readonly array $grid,
    ) {}

    public function isOnGrid(Coordinate $coordinate): bool
    {
        return isset($this->grid[$coordinate->y][$coordinate->x]);
    }

    public function value(Coordinate $coordinate): string
    {
        return $this->grid[$coordinate->y][$coordinate->x];
    }
}
