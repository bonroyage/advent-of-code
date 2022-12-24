<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MMXXII\Day10\CRT;

return new class ('Cathode-Ray Tube') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile();
    }


    public function part1(): Part
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

        return new Part(
            question: 'Find the signal strength during the 20th, 60th, 100th, 140th, 180th, and 220th cycles. What is the sum of these six signal strengths?',
            answer: array_sum($signalStrength)
        );
    }


    public function part2(): Part
    {
        $crt = $this->crt();

        return new Part(
            question: 'Render the image given by your program. What eight capital letters appear on your CRT?',
            answer: $crt->render(),
        );
    }


    private function crt(): CRT
    {
        $crt = new CRT();

        foreach ($this->input() as $instruction) {
            if ($instruction === 'noop') {
                $crt->noop();
            } else {
                $value = (int)Str::after($instruction, 'addx ');
                $crt->addx($value);
            }
        }

        return $crt;
    }

};
