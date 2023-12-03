<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Lanternfish') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile(',');
    }

    public function part1(): Part
    {
        return new Part(
            question: 'Find a way to simulate lanternfish. How many lanternfish would there be after 80 days?',
            answer: $this->simulate(80),
        );
    }

    public function part2(): Part
    {
        return new Part(
            question: 'How many lanternfish would there be after 256 days?',
            answer: $this->simulate(256),
        );
    }

    private function simulate(int $afterDays): int
    {
        $inState = array_fill(0, 9, 0);

        foreach ($this->input()->all() as $fish) {
            $inState[$fish]++;
        }

        for ($i = 0; $i < $afterDays; $i++) {
            $newInState = array_fill(0, 9, 0);

            foreach ($inState as $state => $count) {
                if ($state === 0) {
                    $newInState[6] += $count;
                    $newInState[8] += $count;
                } else {
                    $newInState[$state - 1] += $count;
                }
            }

            $inState = $newInState;
        }

        return array_sum($inState);
    }
};
