<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day13\Grid;

return new class('Transparent Origami') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL.PHP_EOL);
    }

    private function dots(): Collection
    {
        return str($this->input()[0])
            ->explode(PHP_EOL)
            ->map(fn($point) => explode(',', $point));
    }

    private function instructions(): Collection
    {
        return str($this->input()[1])
            ->explode(PHP_EOL)
            ->map(fn($instruction) => sscanf($instruction, 'fold along %1s=%d'));
    }

    public function part1(): Part
    {
        $grid = new Grid($this->dots()->all());

        foreach ($this->instructions() as [$axis, $position]) {
            if ($axis === 'y') {
                $grid->foldUp($position);
                break;
            } else if ($axis === 'x') {
                $grid->foldLeft($position);
                break;
            }
        }

        return new Part(
            answer: $grid->visible(),
        );
    }

    public function part2(): Part
    {
        $grid = new Grid($this->dots()->all());

        foreach ($this->instructions() as [$axis, $position]) {
            if ($axis === 'y') {
                $grid->foldUp($position);
            } else if ($axis === 'x') {
                $grid->foldLeft($position);
            }
        }

        return new Part(
            answer: trim($grid->prettyPrint()),
        );
    }
};
