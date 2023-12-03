<?php

namespace MMXXII\Day9;

use RuntimeException;

class Knot
{
    private array $visited = [];
    private ?self $chaser = null;

    public function __construct(private array $current)
    {
        $this->visited[] = $current;
    }

    public function makeChaser(): self
    {
        if (isset($this->chaser)) {
            throw new RuntimeException('Chaser already exists');
        }

        return $this->chaser = new self($this->current);
    }

    public function move(array $movement): void
    {
        $this->current = [$this->current[0] + $movement[0], $this->current[1] + $movement[1]];
        $this->visited[] = $this->current;

        if (isset($this->chaser)) {
            $chaserPosition = $this->chaser->position();

            $thisX = $this->current[0];
            $thisY = $this->current[1];

            $otherX = $chaserPosition[0];
            $otherY = $chaserPosition[1];

            $distance = max(1, abs($thisX - $otherX)) // distance across X-axis
                * max(1, abs($thisY - $otherY)); // distance across Y-axis

            if ($distance > 1) {
                $movement = [
                    match (true) {
                        $thisX < $otherX => -1,
                        $thisX > $otherX => 1,
                        default => 0,
                    },

                    match (true) {
                        $thisY < $otherY => -1,
                        $thisY > $otherY => 1,
                        default => 0,
                    },
                ];

                $this->chaser->move($movement);
            }
        }
    }

    public function uniquePositions(): int
    {
        return count(array_unique($this->visited, SORT_REGULAR));
    }

    public function position(): array
    {
        return last($this->visited);
    }
}
