<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day9\Grid;

return new class('Smoke Basin') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $row) => str_split($row));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->input()->toArray());

        $lowest = [];

        for ($y = 0; $y < $grid->numberOfRows(); $y++) {
            for ($x = 0; $x < $grid->numberOfColumns(); $x++) {
                if ($grid->isLowerThanAdjacent($x, $y)) {
                    $lowest[] = $grid->value($x, $y);
                }
            }
        }

        return new Part(
            answer: array_sum($lowest) + count($lowest),
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->input()->toArray());

        $lowest = [];

        for ($y = 0; $y < $grid->numberOfRows(); $y++) {
            for ($x = 0; $x < $grid->numberOfColumns(); $x++) {
                if ($grid->isLowerThanAdjacent($x, $y)) {
                    $lowest[] = [$x, $y];
                }
            }
        }

        $basins = [];

        foreach ($lowest as $point) {
            $basins[] = count($grid->belongsToBasin([], $point[0], $point[1]));
        }

        rsort($basins);

        $top3 = array_slice($basins, 0, 3);

        return new Part(
            answer: array_product($top3),
        );
    }
};
