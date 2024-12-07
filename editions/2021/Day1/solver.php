<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Sonar Sweep') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(7)]
    public function part1(): int
    {
        $input = $this->input();

        $increasing = 0;

        for ($i = 1; $i < $input->count(); $i++) {
            if ($input[$i] > $input[$i - 1]) {
                $increasing++;
            }
        }

        return $increasing;
    }

    #[SampleAnswer(5)]
    public function part2(): int
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

        return $increasing;
    }
};
