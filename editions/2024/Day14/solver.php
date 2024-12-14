<?php

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Restroom Redoubt') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/^p=(\d+),(\d+) v=(-?\d+),(-?\d+)$/', $line, $matches);

                return [
                    'x' => (int) $matches[1],
                    'y' => (int) $matches[2],
                    'vx' => (int) $matches[3],
                    'vy' => (int) $matches[4],
                ];
            });
    }

    #[SampleAnswer(12)]
    public function part1(): int
    {
        return $this->safetyFactor(
            input: $this->input()->all(),
            t: 100,
        );
    }

    #[SampleAnswer(null)]
    public function part2(): int
    {
        if ($this->sample) {
            throw new IncompleteException();
        }

        $w = 101;
        $h = 103;

        $input = $this->input()->all();

        $lowest = [null, INF];

        for ($t = 0; $t < $w * $h; $t++) {
            $value = $this->safetyFactor(
                input: $input,
                t: $t,
            );

            if ($value < $lowest[1]) {
                $lowest = [$t, $value];
            }
        }

        return $lowest[0];
    }

    private function positionAtTime(int $t, int $sx, int $sy, int $vx, int $vy, int $w, int $h): array
    {
        $x = ($sx + $vx * $t) % $w;
        $y = ($sy + $vy * $t) % $h;

        if ($x < 0) {
            $x += $w;
        }

        if ($y < 0) {
            $y += $h;
        }

        return [$x, $y];
    }

    private function safetyFactor(array $input, int $t): int
    {
        if ($this->sample) {
            $w = 11;
            $h = 7;
        } else {
            $w = 101;
            $h = 103;
        }

        $midX = floor($w / 2);
        $midY = floor($h / 2);

        $positions = [
            'NW' => 0,
            'NE' => 0,
            'SE' => 0,
            'SW' => 0,
        ];

        foreach ($input as $robot) {
            [$x, $y] = $this->positionAtTime(
                t: $t,
                sx: $robot['x'],
                sy: $robot['y'],
                vx: $robot['vx'],
                vy: $robot['vy'],
                w: $w,
                h: $h,
            );

            if ($x < $midX && $y < $midY) {
                $positions['NW']++;
            } elseif ($x > $midX && $y < $midY) {
                $positions['NE']++;
            } elseif ($x > $midX && $y > $midY) {
                $positions['SE']++;
            } elseif ($x < $midX && $y > $midY) {
                $positions['SW']++;
            }
        }

        return array_product($positions);
    }
};
