<?php

namespace MMXXIII\Day11;

class Grid
{
    private array $grid;

    private array $emptyX = [];

    private array $emptyY = [];

    public function __construct(array $grid)
    {
        $this->grid = $grid;

        for ($y = 0; $y < count($this->grid); $y++) {
            if (array_diff($this->grid[$y], ['.']) === []) {
                $this->emptyY[] = $y;
            }
        }

        for ($x = 0; $x < count($this->grid[0]); $x++) {
            if (array_diff(array_column($this->grid, $x), ['.']) === []) {
                $this->emptyX[] = $x;
            }
        }
    }

    public function crossesEmptyX(int $start, int $end)
    {
        return array_filter($this->emptyX, fn(int $x) => $x >= min($start, $end) && $x <= max($start, $end));
    }

    public function crossesEmptyY(int $start, int $end)
    {
        return array_filter($this->emptyY, fn(int $y) => $y >= min($start, $end) && $y <= max($start, $end));
    }

    public function distance(array $start, array $end, int $emptyExpansionFactor = 1): int
    {
        $crossesX = $this->crossesEmptyX($start[0], $end[0]);
        $crossesY = $this->crossesEmptyY($start[1], $end[1]);

        $distance = abs($start[0] - $end[0])
            + abs($start[1] - $end[1]);

        return $distance
            + (count($crossesX) * ($emptyExpansionFactor - 1))
            + (count($crossesY) * ($emptyExpansionFactor - 1));
    }

    public function getGrid(): string
    {
        $str = '';

        foreach ($this->grid as $row) {
            $str .= implode('', $row).PHP_EOL;
        }

        return $str;
    }

    public function getGalaxies(): array
    {
        $galaxies = [];

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === '#') {
                    $galaxies[] = [$x, $y];
                }
            }
        }

        return $galaxies;
    }

    public function shortestDistanceWithExpansion(int $factor): int
    {
        $galaxies = $this->getGalaxies();

        $distances = 0;

        for ($i = 0; $i < count($galaxies); $i++) {
            for ($j = $i + 1; $j < count($galaxies); $j++) {
                $distances += $this->distance($galaxies[$i], $galaxies[$j], $factor);
            }
        }

        return $distances;
    }
}
