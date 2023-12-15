<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day14\Polymer;

return new class('Extended Polymerization') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL.PHP_EOL);
    }

    private function template(): string
    {
        return $this->input()[0];
    }

    private function insertionRules(): Collection
    {
        return str($this->input()[1])
            ->explode(PHP_EOL)
            ->map(function ($rule) {
                $segments = explode(' -> ', $rule);

                return [...$segments, $segments[0][0].$segments[1]];
            });
    }

    public function part1(): Part
    {
        $polymer = new Polymer($this->template(), $this->insertionRules()->pluck(1, 0)->all());

        $occurrences = $polymer->run(10);

        return new Part(
            answer: last($occurrences) - head($occurrences),
        );
    }

    public function part2(): Part
    {
        $polymer = new Polymer($this->template(), $this->insertionRules()->pluck(1, 0)->all());

        $occurrences = $polymer->run(40);

        return new Part(
            answer: last($occurrences) - head($occurrences),
        );
    }
};
