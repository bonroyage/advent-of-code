<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day9\Grid;

return new class ('Smoke Basin') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile()
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
            question: 'Find all of the low points on your heightmap. What is the sum of the risk levels of all low points on your heightmap?',
            answer: array_sum($lowest) + count($lowest)
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
            question: 'What do you get if you multiply together the sizes of the three largest basins?',
            answer: array_product($top3)
        );
    }

};
