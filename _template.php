<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile();
    }

    public function part1(): Part
    {
        return new Part(
            question: '',
            answer: null,
        );
    }

    public function part2(): Part
    {
        return new Part(
            question: '',
            answer: null,
        );
    }
};
