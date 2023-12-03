<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Rucksack Reorganization') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile(PHP_EOL);
    }

    public function part1(): Part
    {
        $sum = $this->input()
            ->sum(function (string $backpack) {
                $middle = strlen($backpack) / 2;

                return $this->priority(
                    $this->findOverlaps(
                        substr($backpack, 0, $middle),
                        substr($backpack, $middle),
                    ),
                );
            });

        return new Part(
            question: 'Find the item type that appears in both compartments of each rucksack. What is the sum of the priorities of those item types?',
            answer: $sum,
        );
    }

    public function part2(): Part
    {
        $sum = $this->input()
            ->chunk(3)
            ->sum(function (Collection $group) {
                return $this->priority(
                    $this->findOverlaps(
                        ...$group,
                    ),
                );
            });

        return new Part(
            question: 'Find the item type that corresponds to the badges of each three-Elf group. What is the sum of the priorities of those item types?',
            answer: $sum,
        );
    }

    private function priority(string $letter): int
    {
        $priority = ord(strtoupper($letter)) - ord('A') + 1;

        if (ctype_upper($letter)) {
            $priority += 26;
        }

        return $priority;
    }

    private function findOverlaps(string ...$strings): string
    {
        return head(array_intersect(...array_map(str_split(...), $strings)));
    }
};
