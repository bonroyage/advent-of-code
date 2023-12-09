<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Mirage Maintenance') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => explode(' ', $line));
    }

    public function part1(): Part
    {
        $sum = $this->input()
            ->sum(fn(array $sequence) => $this->nextValue(
                $sequence,
            ));

        return new Part(
            answer: $sum,
        );
    }

    public function part2(): Part
    {
        $sum = $this->input()
            ->sum(fn(array $sequence) => $this->nextValue(
                array_reverse($sequence),
            ));

        return new Part(
            answer: $sum,
        );
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
