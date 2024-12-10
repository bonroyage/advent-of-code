<?php

namespace MMXXIV\Day10;

use App\Utilities\Coordinate;

class ElevatedCoordinate extends Coordinate
{
    public function __construct(
        int $x,
        int $y,
        public readonly int $elevation,
    ) {
        parent::__construct($x, $y);
    }
}
