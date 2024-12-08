<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXII\Day8\Grid;

return new class ('Treetop Tree House') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    #[SampleAnswer(21)]
    public function part1(): int
    {
        $grid = new Grid($this->input()->all());

        $visible = 0;

        foreach ($grid->grid as $y => $row) {
            foreach ($row as $x => $_) {
                if ($grid->isVisible([$x, $y])) {
                    $visible++;
                }
            }
        }

        return $visible;
    }

    #[SampleAnswer(8)]
    public function part2(): int
    {
        $grid = new Grid($this->input()->all());
        $maxViewingDistance = 0;

        foreach ($grid->grid as $y => $row) {
            foreach ($row as $x => $_) {
                $viewingDistance = $grid->viewingDistance([$x, $y]);

                if ($viewingDistance > $maxViewingDistance) {
                    $maxViewingDistance = $viewingDistance;
                }
            }
        }

        return $maxViewingDistance;
    }
};
