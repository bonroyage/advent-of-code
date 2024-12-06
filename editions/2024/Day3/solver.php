<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;

return new class('Mull It Over') extends Day
{
    private function input(string $pattern): Illuminate\Support\Collection
    {
        $contents = $this->getFile();

        preg_match_all($pattern, $contents, $matches, PREG_SET_ORDER);

        return collect($matches);
    }

    #[SampleAnswer(161)]
    public function part1(): int
    {
        return $this->input('/mul\((\d+),(\d+)\)/')
            ->sum(function ($match) {
                return $match[1] * $match[2];
            });
    }

    #[SampleAnswer(48)]
    public function part2(): int
    {
        $input = $this->input('/do\(\)|don\'t\(\)|mul\((\d+),(\d+)\)/');

        $state = true;
        $sum = 0;

        foreach ($input as $match) {
            if (str_starts_with($match[0], 'do(')) {
                $state = true;

                continue;
            }

            if (str_starts_with($match[0], 'don\'t(')) {
                $state = false;

                continue;
            }

            if (!$state) {
                continue;
            }

            $sum += $match[1] * $match[2];
        }

        return $sum;
    }
};
