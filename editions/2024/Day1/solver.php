<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Historian Hysteria') extends Day {
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

    #[SampleAnswer(11)]
    public function part1(): int
    {
        $input = $this->input();

        $leftList = $input->pluck('left')->sort()->values();
        $rightList = $input->pluck('right')->sort()->values();

        $diffs = array_map(
            fn($left, $right) => abs($left - $right),
            $leftList->toArray(),
            $rightList->toArray(),
        );

        return array_sum($diffs);
    }

    #[SampleAnswer(31)]
    public function part2(): int
    {
        $input = $this->input();

        $rightList = $input->pluck('right')->countBy();

        return $input->pluck('left')
            ->sum(fn(int $left) => $left * ($rightList[$left] ?? 0));
    }
};
