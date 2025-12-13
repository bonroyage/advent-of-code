<?php

namespace MMXXV\Day4;

use App\Utilities\Direction;

class Grid
{
    public function __construct(public array $grid) {}

    public function isOnGrid(int $x, int $y): bool
    {
        return isset($this->grid[$y][$x]);
    }

    public function value(int $x, int $y): string
    {
        return $this->grid[$y][$x];
    }

    public function numberOfAdjacentRolls(int $x, int $y): ?int
    {
        $count = 0;

        foreach (Direction::Adjacent as $direction) {
            [$dx, $dy] = $direction->offset();

            if ($this->isOnGrid($x + $dx, $y + $dy) && $this->value($x + $dx, $y + $dy) === '@') {
                $count++;
            }
        }

        return $count;
    }

    public function removeRoll(int $x, int $y): void
    {
        $this->grid[$y][$x] = '.';
    }
}
