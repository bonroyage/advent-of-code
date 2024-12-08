<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Wait For It') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(288)]
    public function part1(): int
    {
        $file = $this->input();

        preg_match_all('/(\d+)/', $file[0], $times);
        preg_match_all('/(\d+)/', $file[1], $distances);

        $races = array_map(
            fn(int $time, int $distance) => $this->options($time, $distance)['count'],
            $times[0],
            $distances[0],
        );

        return array_product($races);
    }

    #[SampleAnswer(71_503)]
    public function part2(): int
    {
        $file = $this->input();

        $time = (int) preg_replace('/\D/', '', $file[0]);
        $distance = (int) preg_replace('/\D/', '', $file[1]);

        $options = $this->options($time, $distance);

        return $options['count'];
    }

    public function options(int $time, int $distance): array
    {
        $ax = 1;
        $bx = -$time;
        $c = $distance;

        $x1 = (-$bx - sqrt($bx * $bx - 4 * $ax * $c)) / (2 * $ax);
        $x2 = (-$bx + sqrt($bx * $bx - 4 * $ax * $c)) / (2 * $ax);

        /*
         * If the x value is round, then it doesn't beat the distance but
         * matches it exactly. So for those cases, we add/subtract 1.
         */

        $x1 = fmod($x1, 1) === 0.0
            ? $x1 + 1
            : ceil($x1);

        $x2 = fmod($x2, 1) === 0.0
            ? $x2 - 1
            : floor($x2);

        return [
            'left' => (int) $x1,
            'right' => (int) $x2,
            'count' => (int) ($x2 - $x1 + 1),
        ];
    }
};
