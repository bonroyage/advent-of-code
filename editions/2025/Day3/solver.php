<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Lobby') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(function ($line) {
            return array_map(fn($char) => (int) $char, str_split($line));
        });
    }

    #[SampleAnswer(357)]
    public function part1()
    {
        return $this->input()->sum(function ($line) {
            return $this->findJoltage($line, 2);
        });
    }

    #[SampleAnswer(3121910778619)]
    public function part2()
    {
        return $this->input()->sum(function ($line) {
            return $this->findJoltage($line, 12);
        });
    }

    private function findJoltage(array $batteries, int $pick): int
    {
        $total = 0;

        while ($pick > 0) {
            $maxIndex = 0;

            foreach (array_slice($batteries, 0, $pick === 1 ? null : -$pick + 1) as $i => $char) {
                if ($char <= $batteries[$maxIndex]) {
                    continue;
                }

                $maxIndex = $i;
            }

            $total = $total * 10 + $batteries[$maxIndex];
            $pick--;
            $batteries = array_slice($batteries, $maxIndex + 1);
        }

        return $total;
    }
};
