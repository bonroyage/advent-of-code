<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

return new class('Red-Nosed Reports') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => explode(' ', $line));
    }

    public function part1(): Part
    {
        $input = $this->input()
            ->filter(function (array $line) {
                return $this->isSafe($line);
            });

        return new Part(
            answer: $input->count(),
        );
    }

    public function part2(): Part
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

        return new Part(
            answer: $input->count(),
        );
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
