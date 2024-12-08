<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\Coordinate;
use Illuminate\Support\Collection;
use MMXXIV\Day8\Grid;

return new class ('Resonant Collinearity') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(14)]
    public function part1(): int
    {
        $grid = new Grid($this->input()->all());

        return $grid->antinodes(function (Coordinate $antenna, Coordinate $offset, Closure $isOnGrid) {
            $antinode = $antenna->move($offset->x, $offset->y);

            if ($isOnGrid($antinode)) {
                yield $antinode;
            }
        });
    }

    #[SampleAnswer(34)]
    public function part2(): int
    {
        $grid = new Grid($this->input()->all());

        return $grid->antinodes(function (Coordinate $antenna, Coordinate $offset, Closure $isOnGrid) {
            yield $antenna;
            $antinode = $antenna->move($offset->x, $offset->y);

            while ($isOnGrid($antinode)) {
                yield $antinode;
                $antinode = $antinode->move($offset->x, $offset->y);
            }
        });
    }
};
