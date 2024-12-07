<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MMXXII\Day10\CRT;

return new class('Cathode-Ray Tube') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(13_140)]
    public function part1(): int
    {
        $crt = $this->crt();

        $signalStrength = [
            $crt->signalStrength(20),
            $crt->signalStrength(60),
            $crt->signalStrength(100),
            $crt->signalStrength(140),
            $crt->signalStrength(180),
            $crt->signalStrength(220),
        ];

        return array_sum($signalStrength);
    }

    #[SampleAnswer(<<<'ANSWER'
##..##..##..##..##..##..##..##..##..##..
###...###...###...###...###...###...###.
####....####....####....####....####....
#####.....#####.....#####.....#####.....
######......######......######......####
#######.......#######.......#######.....
ANSWER)]
    public function part2(): string
    {
        $crt = $this->crt();

        return $crt->render();
    }

    private function crt(): CRT
    {
        $crt = new CRT();

        foreach ($this->input() as $instruction) {
            if ($instruction === 'noop') {
                $crt->noop();
            } else {
                $value = (int) Str::after($instruction, 'addx ');
                $crt->addx($value);
            }
        }

        return $crt;
    }
};
