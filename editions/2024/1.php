<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Historian Hysteria') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function ($line) {
                preg_match('/^(\d+)\s+(\d+)$/', $line, $matches);

                return [
                    'left' => (int) $matches[1],
                    'right' => (int) $matches[2],
                ];
            });
    }

    public function part1(): Part
    {
        $input = $this->input();

        $leftList = $input->pluck('left')->sort()->values();
        $rightList = $input->pluck('right')->sort()->values();

        $diffs = array_map(
            fn($left, $right) => abs($left - $right),
            $leftList->toArray(),
            $rightList->toArray(),
        );

        return new Part(
            answer: array_sum($diffs),
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $rightList = $input->pluck('right')->countBy();

        $value = $input->pluck('left')
            ->sum(fn(int $left) => $left * ($rightList[$left] ?? 0));

        return new Part(
            answer: $value,
        );
    }
};
