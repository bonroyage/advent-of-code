<?php

use App\Solver\Day;
use App\Solver\Part;
use App\Solver\SampleAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

return new class('Distress Signal') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->filter()
            ->map(fn($packet) => json_decode($packet, true));
    }

    #[SampleAnswer(13)]
    public function part1(): int
    {
        $packets = $this->input()->chunk(2)->map->values();

        $correctPackets = [];

        foreach ($packets as $index => $packet) {
            if ($this->compare($packet[0], $packet[1])) {
                $correctPackets[] = $index + 1; // Packets are 1-indexed
            }
        }

        return array_sum($correctPackets);
    }

    #[SampleAnswer(140)]
    public function part2(): int
    {
        $packets = $this->input();
        $packets[] = [[2]];
        $packets[] = [[6]];

        $packets = $packets->sort(function ($left, $right) {
            $cmp = $this->compare($left, $right);

            if ($cmp === true) {
                return -1;
            } else if ($cmp === false) {
                return 1;
            }

            return 0;
        })->values();

        $firstDivider = $packets->search([[2]]) + 1;
        $secondDivider = $packets->search([[6]]) + 1;

        return $firstDivider * $secondDivider;
    }

    private function compare(array|int|null $left, array|int|null $right): ?bool
    {
        // If the left list runs out of items first, the inputs are in the right order.
        if ($left === null && $right !== null) {
            return true;
        }

        // If the right list runs out of items first, the inputs are not in the right order.
        if ($right === null && $left !== null) {
            return false;
        }

        // If both values are integers, the lower integer should come first.
        if (is_int($left) && is_int($right)) {
            // If the left integer is lower than the right integer, the inputs are in the right order.
            if ($left < $right) {
                return true;
            }

            // If the left integer is higher than the right integer, the inputs are not in the right order.
            if ($left > $right) {
                return false;
            }

            // Otherwise, the inputs are the same integer; continue checking the next part of the input.
            return null;
        }

        $left = Arr::wrap($left);
        $right = Arr::wrap($right);

        for ($i = 0; ; $i++) {
            // If the lists are the same length and no comparison makes a decision
            // about the order, continue checking the next part of the input.
            if (!isset($left[$i]) && !isset($right[$i])) {
                return null;
            }

            if (($res = $this->compare($left[$i] ?? null, $right[$i] ?? null)) !== null) {
                return $res;
            }
        }
    }
};
