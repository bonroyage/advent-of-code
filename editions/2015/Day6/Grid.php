<?php

namespace MMXV\Day6;

use Closure;
use Illuminate\Support\Arr;

class Grid
{
    private array $grid;

    public function __construct(int $width, int $height)
    {
        $row = array_fill(0, $width, 0);
        $this->grid = array_fill(0, $height, $row);
    }

    public function modify(array $start, array $end, $do): void
    {
        foreach (range($start[1], $end[1]) as $y) {
            foreach (range($start[0], $end[0]) as $x) {
                $this->grid[$y][$x] = $do instanceof Closure ? $do($this->grid[$y][$x]) : $do;
            }
        }
    }

    public function flat(): array
    {
        return Arr::flatten($this->grid);
    }
}
