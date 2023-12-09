<?php

namespace MMXXII\Day14;

class Grid
{
    private array $grid;

    private ?int $floorY = null;

    public function __construct()
    {
        $this->grid = [
            ['+'],
        ];
    }

    public function addPathOfRocks(array $path)
    {
        [$lastX, $lastY] = array_shift($path);

        foreach ($path as [$x, $y]) {
            foreach (range($lastY, $y) as $dy) {
                foreach (range($lastX, $x) as $dx) {
                    $dx -= 500;

                    $this->widen($dx);
                    $this->deepen($dy);
                    $this->grid[$dy][$dx] = '#';
                }
            }

            [$lastX, $lastY] = [$x, $y];
        }
    }

    public function dropSand(array $node): void
    {
        [$x, $y] = $node;

        $this->widen($x);
        $this->deepen($y);
        $this->grid[$y][$x] = 'o';
    }

    public function isAbyss(array $node): bool
    {
        [$x, $y] = $node;

        return !isset($this->grid[$y][$x]);
    }

    public function isFloor(array $node): bool
    {
        [$x, $y] = $node;

        return $y >= $this->floorY;
    }

    public function isBlocked($node): bool
    {
        [$x, $y] = $node;

        if ($this->floorY === $y) {
            return true;
        }

        return ($this->grid[$y][$x] ?? '.') !== '.';
    }

    public function widen(int $x): void
    {
        if (isset($this->grid[0][$x])) {
            return;
        }

        $first = array_key_first($this->grid[0]);
        $last = array_key_last($this->grid[0]);

        $range = range(min($x, $first), max($x, $last));

        foreach ($this->grid as $y => $row) {
            $this->grid[$y] = $row + array_fill_keys($range, $y === $this->floorY ? '#' : '.');
            ksort($this->grid[$y], SORT_NUMERIC);
        }
    }

    public function deepen(int $y): void
    {
        if (isset($this->grid[$y])) {
            return;
        }

        for ($i = 1; $i <= $y; $i++) {
            $this->grid[$i] ??= array_map(fn() => $i === $this->floorY ? '#' : '.', $this->grid[0]);
        }
    }

    public function getGrid(): string
    {
        $str = '';

        foreach ($this->grid as $row) {
            $str .= implode('', $row).PHP_EOL;
        }

        return $str;
    }

    public function setFloor(): void
    {
        $this->floorY = array_key_last($this->grid) + 2;
        $this->deepen($this->floorY);
    }
}
