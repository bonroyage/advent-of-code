<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXV\Day4\Grid;

return new class ('Printing Department') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(function (string $line) {
            return str_split($line);
        });
    }

    #[SampleAnswer(13)]
    public function part1()
    {
        $grid = new Grid($this->input()->all());

        $rolls = 0;

        foreach ($grid->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === '@' && $grid->numberOfAdjacentRolls($x, $y) < 4) {
                    $rolls++;
                }
            }
        }

        return $rolls;
    }

    #[SampleAnswer(43)]
    public function part2()
    {
        $grid = new Grid($this->input()->all());

        $rolls = 0;

        do {
            $removedInIteration = false;

            foreach ($grid->grid as $y => $row) {
                foreach ($row as $x => $value) {
                    if ($value === '@' && $grid->numberOfAdjacentRolls($x, $y) < 4) {
                        $rolls++;
                        $grid->removeRoll($x, $y);
                        $removedInIteration = true;
                    }
                }
            }
        } while ($removedInIteration);

        return $rolls;
    }
};
