<?php

namespace MMXXII\Day12;

class Grid
{
    public readonly array $a;
    public readonly array $S;
    public readonly array $E;

    public function __construct(public readonly array $grid)
    {
        $a = [];

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === 'E') {
                    $this->E = [$x, $y];
                } elseif ($cell === 'S') {
                    $this->S = [$x, $y];
                    $a[] = $this->S;
                } elseif ($cell === 'a') {
                    $a[] = [$x, $y];
                }
            }
        }

        $this->a = $a;
    }

    public function nextNodes(array $node): array
    {
        return collect([[0, 1], [0, -1], [1, 0], [-1, 0]])
            ->map(fn($neighbour) => [$node[0] + $neighbour[0], $node[1] + $neighbour[1]])
            ->filter(fn($neighbour) => isset($this->grid[$neighbour[1]][$neighbour[0]]))
            ->all();
    }

    public function run(array ...$startingPoints)
    {
        $distances = array_fill(0, count($this->grid), array_fill(0, count($this->grid[0]), PHP_INT_MAX));

        $queue = [$this->E];
        $distances[$this->E[1]][$this->E[0]] = 0;

        while (!empty($queue)) {
            $current = array_shift($queue);

            $currentDistance = $distances[$current[1]][$current[0]];
            $currentElevation = $this->elevation($current);

            foreach ($this->nextNodes($current) as $neighbour) {
                $neighbourDistance = $distances[$neighbour[1]][$neighbour[0]];
                $neighbourElevation = $this->elevation($neighbour);

                // The neighbour (where you're technically coming from since we're going in reverse)
                // can be at most 1 unit of elevation lower than where we are now.
                if ($neighbourElevation < $currentElevation - 1) {
                    continue;
                }

                if ($neighbourDistance > ($currentDistance + 1)) {
                    $queue[] = $neighbour;
                    $distances[$neighbour[1]][$neighbour[0]] = $currentDistance + 1;
                }
            }
        }

        $shortestPath = PHP_INT_MAX;

        foreach ($startingPoints as $startingPoint) {
            if ($distances[$startingPoint[1]][$startingPoint[0]] < $shortestPath) {
                $shortestPath = $distances[$startingPoint[1]][$startingPoint[0]];
            }
        }

        return $shortestPath;
    }

    public function elevation(array $node): int
    {
        $letter = $this->grid[$node[1]][$node[0]];

        $letter = match ($letter) {
            'S' => 'a',
            'E' => 'z',
            default => $letter,
        };

        return ord($letter) - 96;
    }
}
