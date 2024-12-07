<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('The Treachery of Whales') extends Day
{
    private function input(): Collection
    {
        return $this->getFile()
            ->explode(',');
    }

    #[SampleAnswer(37)]
    public function part1(): int
    {
        $costs = $this->calculateCosts(static fn($movement) => $movement);

        return min($costs);
    }

    #[SampleAnswer(168)]
    public function part2(): int
    {
        $costs = $this->calculateCosts(static fn($movement) => ($movement * ($movement + 1)) / 2);

        return min($costs);
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
