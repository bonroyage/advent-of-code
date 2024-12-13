<?php

namespace MMXXIV\Day12;

use App\Utilities\Coordinate;
use App\Utilities\Direction;

class PerimeterCoordinate extends Coordinate
{
    public function __construct(
        int $x,
        int $y,
        public readonly Direction $direction,
    ) {
        parent::__construct($x, $y);
    }
}
