<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXIV\Day4\Grid;

return new class('Ceres Search') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    public function part1(): Part
    {
        $input = new Grid($this->input()->all());

        $count = 0;

        foreach ($input->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $count += $input->checkX(x: $x, y: $y);
            }
        }

        return new Part(
            answer: $count,
        );
    }

    public function part2(): Part
    {
        $input = new Grid($this->input()->all());

        $count = 0;

        foreach ($input->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $count += $input->checkA(x: $x, y: $y);
            }
        }

        return new Part(
            answer: $count,
        );
    }
};
