<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;

return new class ('Cafeteria') extends Day {
    private function input(): array
    {
        [$ranges, $ingredients] = $this->getFileLines(PHP_EOL . PHP_EOL);

        return [
            'ranges' => str($ranges)
                ->explode(PHP_EOL)
                ->map(function ($line) {
                    [$low, $high] = explode('-', $line);

                    return [(int) $low, (int) $high];
                })
                ->toArray(),

            'ingredients' => str($ingredients)
                ->explode(PHP_EOL)
                ->map(fn($line) => (int) $line)
                ->toArray(),
        ];
    }

    #[SampleAnswer(3)]
    public function part1()
    {
        $fresh = 0;
        ['ranges' => $ranges, 'ingredients' => $ingredients] = $this->input();

        foreach ($ingredients as $ingredient) {
            foreach ($ranges as [$low, $high]) {
                if ($ingredient >= $low && $ingredient <= $high) {
                    $fresh++;

                    break;
                }
            }
        }

        return $fresh;
    }

    #[SampleAnswer(14)]
    public function part2()
    {
        $fresh = 0;
        $ranges = $this->input()['ranges'];

        usort($ranges, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        $highest = 0;

        foreach ($ranges as [$low, $high]) {
            if ($low <= $highest) {
                $low = $highest + 1;
            }

            if ($low > $high) {
                continue;
            }

            $highest = $high;

            $fresh += $high - $low + 1;
        }

        return $fresh;
    }
};
