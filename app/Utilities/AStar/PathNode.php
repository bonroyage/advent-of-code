<?php

namespace App\Utilities\AStar;

use App\Utilities\Coordinate;

readonly class PathNode
{
    public int $gScore;

    public int $fScore;

    public function __construct(
        public Coordinate $coordinate,
        public ?self $parent = null,
        public int $moveCost = 0,
        public int $hScore = 0,
    ) {
        $this->gScore = ($this->parent?->gScore ?? 0) + $this->moveCost;
        $this->fScore = $this->gScore + $this->hScore;
    }

    public function path(): array
    {
        $result = [];

        $node = $this;

        while ($node) {
            array_unshift($result, $node->coordinate);
            $node = $node->parent;
        }

        return $result;
    }
}
