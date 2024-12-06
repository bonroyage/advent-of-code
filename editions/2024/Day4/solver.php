<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXIV\Day4\Grid;

return new class('Ceres Search') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    #[SampleAnswer(18)]
    public function part1(): int
    {
        $input = new Grid($this->input()->all());

        $count = 0;

        foreach ($input->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $count += $input->checkX(x: $x, y: $y);
            }
        }

        return $count;
    }

    #[SampleAnswer(9)]
    public function part2(): int
    {
        $input = new Grid($this->input()->all());

        $count = 0;

        foreach ($input->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $count += $input->checkA(x: $x, y: $y);
            }
        }

        return $count;
    }
};
