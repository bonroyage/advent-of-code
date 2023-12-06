<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day8\Grid;

return new class('Treetop Tree House') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => str_split($line));
    }

    public function part1(): Part
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

        return new Part(
            answer: $visible,
        );
    }

    public function part2(): Part
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

        return new Part(
            answer: $maxViewingDistance,
        );
    }
};
