<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;

return new class('Tuning Trouble') extends Day
{
    private function input(): array
    {
        return str_split($this->getFile());
    }

    #[SampleAnswer(11)]
    public function part1(): int
    {
        return $this->findMarker(4);
    }

    #[SampleAnswer(26)]
    public function part2(): int
    {
        return $this->findMarker(14);
    }

    private function findMarker(int $uniqueLength): ?int
    {
        $input = $this->input();

        $input = array_map(null, ...array_map(fn($offset) => array_slice($input, $offset), range(0, $uniqueLength - 1)));

        foreach ($input as $index => $window) {
            if (array_unique($window) === $window) {
                return $index + $uniqueLength;
            }
        }

        return null;
    }
};
