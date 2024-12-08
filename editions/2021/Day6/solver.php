<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Lanternfish') extends Day {
    private function input(): Collection
    {
        return $this->getFile()
            ->explode(',');
    }

    #[SampleAnswer(5934)]
    public function part1(): int
    {
        return $this->simulate(80);
    }

    #[SampleAnswer(26_984_457_539)]
    public function part2(): int
    {
        return $this->simulate(256);
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
