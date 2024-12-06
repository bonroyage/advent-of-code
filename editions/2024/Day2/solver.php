<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

return new class('Red-Nosed Reports') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => explode(' ', $line));
    }

    #[SampleAnswer(2)]
    public function part1(): int
    {
        $input = $this->input()
            ->filter(function (array $line) {
                return $this->isSafe($line);
            });

        return $input->count();
    }

    #[SampleAnswer(4)]
    public function part2(): int
    {
        $input = $this->input()
            ->filter(function (array $line) {
                if ($this->isSafe($line)) {
                    return true;
                }

                for ($i = 0; $i < count($line); $i++) {
                    if ($this->isSafe(Arr::except($line, $i))) {
                        return true;
                    }
                }

                return false;
            });

        return $input->count();
    }

    private function isSafe(array $line): bool
    {
        $line = array_values($line);

        $diff = abs($line[1] - $line[0]);

        if ($diff < 1 || $diff > 3) {
            return false;
        }

        $shouldBeDecreasing = $line[1] < $line[0];

        for ($i = 2; $i < count($line); $i++) {
            if (($line[$i] < $line[$i - 1]) !== $shouldBeDecreasing) {
                return false;
            }

            $diff = abs($line[$i] - $line[$i - 1]);

            if ($diff < 1 || $diff > 3) {
                return false;
            }
        }

        return true;
    }
};
