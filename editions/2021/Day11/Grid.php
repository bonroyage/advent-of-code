<?php

namespace MMXXI\Day11;

class Grid
{
    private array $grid;
    private int $flashesInStep = 0;

    public function __construct(public readonly array $original)
    {
        $this->grid = $this->original;
    }

    public function count(): int
    {
        return $this->numberOfColumns() * $this->numberOfRows();
    }

    public function numberOfRows(): int
    {
        return count($this->grid);
    }

    public function numberOfColumns(): int
    {
        return count($this->grid[0]);
    }

    public function value(int $x, int $y): int
    {
        return $this->grid[$y][$x];
    }

    public function step(): int
    {
        $this->flashesInStep = 0;

        // First, the energy level of each octopus increases by 1.
        for ($y = 0; $y < $this->numberOfRows(); $y++) {
            for ($x = 0; $x < $this->numberOfColumns(); $x++) {
                $this->grid[$y][$x]++;
            }
        }

        $currentGrid = $this->grid;

        // Then, any octopus with an energy level greater than 9 flashes.
        for ($y = 0; $y < $this->numberOfRows(); $y++) {
            for ($x = 0; $x < $this->numberOfColumns(); $x++) {
                if ($currentGrid[$y][$x] === 10) {
                    $this->flash($x, $y);
                }
            }
        }

        // Finally, any octopus that flashed during this step has its energy
        // level set to 0, as it used all of its energy to flash.
        for ($y = 0; $y < $this->numberOfRows(); $y++) {
            for ($x = 0; $x < $this->numberOfColumns(); $x++) {
                if ($this->grid[$y][$x] > 9) {
                    $this->grid[$y][$x] = 0;
                }
            }
        }

        return $this->flashesInStep;
    }

    private function flash(int $x, int $y): void
    {
        $this->flashesInStep++;

        for ($yStep = -1; $yStep <= 1; $yStep++) {
            for ($xStep = -1; $xStep <= 1; $xStep++) {
                // Ignore the non-existent coordinates due to corners
                if (!isset($this->grid[$y + $yStep][$x + $xStep])) {
                    continue;
                }

                // This increases the energy level of all adjacent octopuses by 1, including
                // octopuses that are diagonally adjacent.
                $this->grid[$y + $yStep][$x + $xStep]++;

                // This process continues as long as new octopuses keep having their energy
                // level increased beyond 9. (An octopus can only flash at most once per step.)
                if ($this->grid[$y + $yStep][$x + $xStep] === 10) {
                    $this->flash($x + $xStep, $y + $yStep);
                }
            }
        }
    }
}
