<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('The Treachery of Whales') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFile()
            ->explode(',');
    }

    public function part1(): Part
    {
        $costs = $this->calculateCosts(static fn($movement) => $movement);

        return new Part(
            answer: min($costs),
        );
    }

    public function part2(): Part
    {
        $costs = $this->calculateCosts(static fn($movement) => ($movement * ($movement + 1)) / 2);

        return new Part(
            answer: min($costs),
        );
    }

    private function calculateCosts(Closure $calculator): array
    {
        $input = $this->input()->all();
        $costs = array_fill(0, max($input), null);
        $occurrences = array_count_values($input);

        array_walk($costs, function (&$cost, $fixedPosition) use ($calculator, $occurrences) {
            foreach ($occurrences as $position => $count) {
                $movement = abs($position - $fixedPosition);
                $cost += $calculator($movement) * $count;
            }
        });

        return $costs;
    }
};
