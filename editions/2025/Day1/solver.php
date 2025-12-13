<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Secret Entrance') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function ($line) {
                preg_match('/^(L|R)(\d+)$/', $line, $matches);

                if ($matches[1] === 'L') {
                    return (int) $matches[2] * -1;
                } else {
                    return (int) $matches[2];
                }
            });
    }

    #[SampleAnswer(3)]
    public function part1()
    {
        $pointer = 50;
        $atZero = 0;

        foreach ($this->input() as $move) {
            $pointer = $this->mod($pointer + $move, 100);

            if ($pointer === 0) {
                $atZero++;
            }
        }

        return $atZero;
    }

    #[SampleAnswer(6)]
    public function part2()
    {
        $pointer = 50;
        $atZero = 0;

        foreach ($this->input() as $move) {
            $fullLoops = (int) floor(abs($move) / 100);
            $atZero += $fullLoops;
            $move += $fullLoops * ($move < 0 ? 100 : -100);

            if ($move === 0) {
                continue;
            }

            $oldPointer = $pointer;
            $pointer += $move;

            if ($pointer >= 100 || ($pointer <= 0 && $oldPointer !== 0)) {
                $atZero++;
            }

            $pointer = $this->mod($pointer, 100);
        }

        return $atZero;
    }

    private function mod(int $num, int $mod): int
    {
        return ($mod + ($num % $mod)) % $mod;
    }
};
