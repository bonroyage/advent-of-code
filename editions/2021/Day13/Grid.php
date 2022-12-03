<?php

namespace MMXXI\Day13;

class Grid
{

    private array $grid;


    public function __construct(array $points)
    {
        $this->grid = $this->makeGrid(
            width: max(array_column($points, 0)) + 1,
            height: max(array_column($points, 1)) + 1,
        );

        foreach ($points as [$x, $y]) {
            $this->grid[$y][$x] = true;
        }
    }


    private function makeGrid(?int $width = null, ?int $height = null): array
    {
        return array_fill(0, $height ?? $this->height(), array_fill(0, $width ?? $this->width(), false));
    }


    public function height(): int
    {
        return count($this->grid);
    }


    public function width(): int
    {
        return count($this->grid[0]);
    }


    public function prettyPrint(array $grid = null): string
    {
        $str = '';

        foreach ($grid ?? $this->grid as $row) {
            foreach ($row as $point) {
                $str .= $point ? '#' : '.';
            }
            $str .= PHP_EOL;
        }

        return $str;
    }


    private function fold(array $canvas, int $foldAt): array
    {
        $staticPart = array_reverse(array_slice($canvas, 0, $foldAt));
        $foldedPart = array_slice($canvas, $foldAt + 1);

        return [
            $staticPart,
            $foldedPart,
            max(count($staticPart), count($foldedPart)),
        ];
    }


    public function foldUp(int $foldAt): void
    {
        [$topHalf, $bottomHalf, $height] = $this->fold($this->grid, $foldAt);
        $foldedGrid = $this->makeGrid(height: $height);

        foreach ($foldedGrid as $y => $row) {
            foreach ($row as $x => $_) {
                $foldedGrid[$y][$x] = ($topHalf[$y][$x] ?? false) || ($bottomHalf[$y][$x] ?? false);
            }
        }

        $this->grid = array_reverse($foldedGrid);
    }


    public function foldLeft(int $foldAt): void
    {
        [$_, $_, $width] = $this->fold($this->grid[0], $foldAt);
        $foldedGrid = $this->makeGrid(width: $width);

        foreach ($foldedGrid as $y => $row) {
            [$leftHalf, $rightHalf] = $this->fold($this->grid[$y], $foldAt);

            foreach ($row as $x => $_) {
                $foldedGrid[$y][$x] = ($leftHalf[$x] ?? false) || ($rightHalf[$x] ?? false);
            }

            $foldedGrid[$y] = array_reverse($foldedGrid[$y]);
        }

        $this->grid = $foldedGrid;
    }


    public function visible(): int
    {
        return collect($this->grid)
            ->flatten()
            ->filter()
            ->count();
    }

}
