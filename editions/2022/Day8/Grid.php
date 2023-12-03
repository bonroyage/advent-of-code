<?php

namespace MMXXII\Day8;

class Grid
{
    public function __construct(public readonly array $grid)
    {
    }

    public function isVisible(array $node): bool
    {
        $height = $this->height($node);

        return $this->isVisibleOnSides($this->grid[$node[1]], $node[0], $height)
            || $this->isVisibleOnSides(array_column($this->grid, $node[0]), $node[1], $height);
    }

    private function isVisibleOnSides(array $sightline, int $offset, int $height): bool
    {
        // Left column or top row
        if ($offset === 0) {
            return true;
        }

        // Right column or bottom row
        if ($offset + 1 === count($sightline)) {
            return true;
        }

        $before = max(array_slice($sightline, 0, $offset));
        $after = max(array_slice($sightline, $offset + 1));

        return $before < $height || $after < $height;
    }

    public function viewingDistance(array $node): int
    {
        return array_product([
            'left' => $this->countTallerTrees($node, -1, 0),
            'right' => $this->countTallerTrees($node, 1, 0),
            'top' => $this->countTallerTrees($node, 0, -1),
            'bottom' => $this->countTallerTrees($node, 0, 1),
        ]);
    }

    private function countTallerTrees(array $node, int $horizontal, int $vertical): int
    {
        $distance = 0;
        $startingHeight = $this->height($node);

        $x = $node[0] + $horizontal;
        $y = $node[1] + $vertical;

        while (isset($this->grid[$y][$x])) {
            // We can always see the tree that's next to the starting node, so
            // we can increase the distance by 1
            $distance++;

            // Store the next for the neighbouring node
            $neighbourHeight = $this->height([$x, $y]);

            // If the height of the tree is equal in height or taller than the
            // original tree, the view is being blocked and we can stop.
            if ($neighbourHeight >= $startingHeight) {
                break;
            }

            // Move over
            $x += $horizontal;
            $y += $vertical;
        }

        return $distance;
    }

    public function height(array $node): int
    {
        return $this->grid[$node[1]][$node[0]];
    }
}
