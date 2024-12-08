<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXIV\Day7\Equation;

return new class ('Bridge Repair') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match_all('/\d+/', $line, $matches);

                return array_map(fn($v) => (int) $v, $matches[0]);
            });
    }

    #[SampleAnswer(3749)]
    public function part1(): int
    {
        return $this->calculate();
    }

    #[SampleAnswer(11387)]
    public function part2(): int
    {
        Equation::$withConcatenation = true;

        return $this->calculate();
    }

    private function calculate(): int
    {
        $sum = 0;

        foreach ($this->input() as $line) {
            $target = array_shift($line);

            if (Equation::isPossible($target, $line)) {
                $sum += $target;
            }
        }

        return $sum;
    }
};
