<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Claw Contraption') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL . PHP_EOL)->map(function (string $block) {
            preg_match('/Button A: X\+(\d+), Y\+(\d+)\nButton B: X\+(\d+), Y\+(\d+)\nPrize: X=(\d+), Y=(\d+)/', $block, $matches);

            return [
                'Xa' => (int) $matches[1],
                'Ya' => (int) $matches[2],
                'Xb' => (int) $matches[3],
                'Yb' => (int) $matches[4],
                'Tx' => (int) $matches[5],
                'Ty' => (int) $matches[6],
            ];
        });
    }

    #[SampleAnswer(480)]
    public function part1(): int
    {
        $input = $this->input();

        $cost = 0;

        foreach ($input as $game) {
            $cost += $this->calculateCost(
                Xa: $game['Xa'],
                Ya: $game['Ya'],
                Xb: $game['Xb'],
                Yb: $game['Yb'],
                Tx: $game['Tx'],
                Ty: $game['Ty'],
            );
        }

        return $cost;
    }

    public function part2()
    {
        $input = $this->input();

        $cost = 0;

        foreach ($input as $game) {
            $cost += $this->calculateCost(
                Xa: $game['Xa'],
                Ya: $game['Ya'],
                Xb: $game['Xb'],
                Yb: $game['Yb'],
                Tx: 10_000_000_000_000 + $game['Tx'],
                Ty: 10_000_000_000_000 + $game['Ty'],
            );
        }

        return $cost;
    }

    private function calculateCost(int $Xa, int $Ya, int $Xb, int $Yb, int $Tx, int $Ty): int
    {
        $a = ($Yb * $Tx - $Xb * $Ty) / ($Xa * $Yb - $Ya * $Xb);
        $b = ($Tx - $Xa * $a) / $Xb;

        if (is_int($a) && is_int($b)) {
            return $a * 3 + $b;
        }

        return 0;
    }
};
