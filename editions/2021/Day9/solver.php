<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day9\Grid;

return new class ('Smoke Basin') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $row) => str_split($row));
    }

    #[SampleAnswer(15)]
    public function part1(): int
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

        return array_sum($lowest) + count($lowest);
    }

    #[SampleAnswer(1_134)]
    public function part2(): int
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

        return array_product($top3);
    }
};
