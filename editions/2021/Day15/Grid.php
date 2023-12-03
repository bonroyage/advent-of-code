<?php

namespace MMXXI\Day15;

class Grid
{
    private array $destination;

    public function __construct(public readonly array $grid)
    {
        $this->destination = [array_key_last(last($this->grid)), array_key_last($this->grid)];
    }

    public function nextNodes(array $node): array
    {
        return collect([[0, 1], [0, -1], [1, 0], [-1, 0]])
            ->map(fn($neighbour) => [$node[0] + $neighbour[0], $node[1] + $neighbour[1]])
            ->filter(fn($neighbour) => isset($this->grid[$neighbour[1]][$neighbour[0]]))
            ->all();
    }

    public function run()
    {
        $risk = array_fill(0, count($this->grid), array_fill(0, count($this->grid[0]), PHP_INT_MAX));

        $queue = [[0, 0]];
        $risk[0][0] = 0;

        while (!empty($queue)) {
            $current = array_shift($queue);

            $currentRisk = $risk[$current[1]][$current[0]];

            foreach ($this->nextNodes($current) as $neighbour) {
                $neighbourRisk = $risk[$neighbour[1]][$neighbour[0]];
                $value = $this->value($neighbour);

                if ($neighbourRisk > ($currentRisk + $value)) {
                    $queue[] = $neighbour;
                    $risk[$neighbour[1]][$neighbour[0]] = $currentRisk + $value;
                }
            }
        }

        return $risk[$this->destination[1]][$this->destination[0]];
    }

    public function value(array $node): int
    {
        return $this->grid[$node[1]][$node[0]];
    }
}
