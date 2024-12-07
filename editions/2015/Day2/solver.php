<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('I Was Told There Would Be No Math') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()->map(function (string $line) {
            preg_match('/^(\d+)x(\d+)x(\d+)$/', $line, $matches);

            return [
                'l' => (int) $matches[1],
                'w' => (int) $matches[2],
                'h' => (int) $matches[3],
            ];
        });
    }

    public function part1(): Part
    {
        $areas = $this->input()->map(function (array $dimensions) {
            $sides = [
                'lw' => $dimensions['l'] * $dimensions['w'],
                'wh' => $dimensions['w'] * $dimensions['h'],
                'hl' => $dimensions['h'] * $dimensions['l'],
            ];

            return array_sum($sides) * 2 + min($sides);
        });

        return new Part(
            answer: $areas->sum(),
        );
    }

    public function part2(): Part
    {
        $ribbon = $this->input()->map(function (array $dimensions) {
            $bow = array_product($dimensions);

            sort($dimensions);
            $box = array_sum(array_slice($dimensions, 0, 2)) * 2;

            return $bow + $box;
        });

        return new Part(
            answer: $ribbon->sum(),
        );
    }
};
