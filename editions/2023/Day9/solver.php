<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Mirage Maintenance') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => explode(' ', $line));
    }

    #[SampleAnswer(114)]
    public function part1(): int
    {
        return $this->input()
            ->sum(fn(array $sequence) => $this->nextValue(
                $sequence,
            ));
    }

    #[SampleAnswer(2)]
    public function part2(): int
    {
        return $this->input()
            ->sum(fn(array $sequence) => $this->nextValue(
                array_reverse($sequence),
            ));

    }

    private function nextValue(array $sequence): int
    {
        $sequences = [
            $sequence,
        ];

        while (array_diff(last($sequences), [0]) !== []) {
            $nextSequence = [];
            $lastSequence = last($sequences);

            for ($i = 0; $i < count($lastSequence) - 1; $i++) {
                $nextSequence[] = $lastSequence[$i + 1] - $lastSequence[$i];
            }

            $sequences[] = $nextSequence;
        }

        $sequences = array_reverse($sequences);

        foreach ($sequences as $i => $sequence) {
            $sequences[$i][] = $i === 0
                ? 0
                : last($sequences[$i - 1]) + last($sequence);
        }

        return last(last($sequences));
    }
};
