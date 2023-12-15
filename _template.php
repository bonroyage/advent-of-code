<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        return new Part(
            answer: null,
        );
    }

    public function part2(): Part
    {
        return new Part(
            answer: null,
        );
    }
};
