<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Sonar Sweep') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        $input = $this->input();

        $increasing = 0;

        for ($i = 1; $i < $input->count(); $i++) {
            if ($input[$i] > $input[$i - 1]) {
                $increasing++;
            }
        }

        return new Part(
            answer: $increasing,
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $increasing = 0;

        $windows = array_map(null, $input->all(), array_slice($input->all(), 1), array_slice($input->all(), 2));
        $sumOfWindows = array_map(fn($window) => array_sum($window), $windows);

        for ($i = 1; $i < count($sumOfWindows); $i++) {
            if ($sumOfWindows[$i] > $sumOfWindows[$i - 1]) {
                $increasing++;
            }
        }

        return new Part(
            answer: $increasing,
        );
    }
};
