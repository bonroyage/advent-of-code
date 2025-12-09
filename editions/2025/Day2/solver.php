<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Gift Shop') extends Day {
    private function input(): Collection
    {
        return $this->getFile()
            ->replace(PHP_EOL, '')
            ->explode(',')
            ->map(function ($line) {
                preg_match('/^(\d+)-(\d+)$/', $line, $matches);

                return [
                    (int) $matches[1],
                    (int) $matches[2],
                ];
            });
    }

    #[SampleAnswer(1227775554)]
    public function part1()
    {
        $invalid = 0;

        foreach ($this->input() as [$a, $b]) {
            for ($i = $a; $i <= $b; $i++) {
                if (preg_match('/^(?<repeat>\d+)\k<repeat>$/', (string) $i)) {
                    $invalid += $i;
                }
            }
        }

        return $invalid;
    }

    #[SampleAnswer(4174379265)]
    public function part2()
    {
        $invalid = 0;

        foreach ($this->input() as [$a, $b]) {
            for ($i = $a; $i <= $b; $i++) {
                if (preg_match('/^(?<repeat>\d+)\k<repeat>+$/', (string) $i)) {
                    $invalid += $i;
                }
            }
        }

        return $invalid;
    }
};
