<?php

namespace MMXXI\Day9;

class Grid
{
    public function __construct(public readonly array $grid) {}

    public function numberOfRows(): int
    {
        return count($this->grid);
    }

    public function numberOfColumns(): int
    {
        return count($this->grid[0]);
    }

    public function isTopRow(int $y): bool
    {
        return $y === 0;
    }

    public function isBottomRow(int $y): bool
    {
        return $y === count($this->grid) - 1;
    }

    public function isLeftColumn(int $x): bool
    {
        return $x === 0;
    }

    public function isRightColumn(int $x): bool
    {
        return $x === count($this->grid[0]) - 1;
    }

    public function value(int $x, int $y): int
    {
        return $this->grid[$y][$x];
    }

    public function isLowerThanAdjacent(int $x, int $y): bool
    {
        $value = $this->value($x, $y);

        if (!$this->isLeftColumn($x)) {
            if ($value >= $this->value($x - 1, $y)) {
                return false;
            }
        }

        if (!$this->isRightColumn($x)) {
            if ($value >= $this->value($x + 1, $y)) {
                return false;
            }
        }

        if (!$this->isTopRow($y)) {
            if ($value >= $this->value($x, $y - 1)) {
                return false;
            }
        }

        if (!$this->isBottomRow($y)) {
            if ($value >= $this->value($x, $y + 1)) {
                return false;
            }
        }

        return true;
    }

    public function belongsToBasin(array $basin, int $x, int $y): array
    {
        $basin[] = [$x, $y];

        if (!$this->isLeftColumn($x)) {
            if (!in_array([$x - 1, $y], $basin, true) && $this->value($x - 1, $y) < 9) {
                $basin = $this->belongsToBasin($basin, $x - 1, $y);
            }
        }

        if (!$this->isRightColumn($x)) {
            if (!in_array([$x + 1, $y], $basin, true) && $this->value($x + 1, $y) < 9) {
                $basin = $this->belongsToBasin($basin, $x + 1, $y);
            }
        }

        if (!$this->isTopRow($y)) {
            if (!in_array([$x, $y - 1], $basin, true) && $this->value($x, $y - 1) < 9) {
                $basin = $this->belongsToBasin($basin, $x, $y - 1);
            }
        }

        if (!$this->isBottomRow($y)) {
            if (!in_array([$x, $y + 1], $basin, true) && $this->value($x, $y + 1) < 9) {
                $basin = $this->belongsToBasin($basin, $x, $y + 1);
            }
        }

        return $basin;
    }
}
