<?php

namespace App\Utilities\AStar;

use App\Utilities\Coordinate;

interface Pathfinding
{
    /**
     * @return Coordinate[]
     */
    public function neighbours(Coordinate $for): array;

    public function costToMove(Coordinate $from, Coordinate $to): int;

    public function distance(Coordinate $from, Coordinate $to): int;

    public function goalReached(Coordinate $at, Coordinate $goal): bool;
}
