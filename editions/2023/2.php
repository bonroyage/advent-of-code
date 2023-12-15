<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Cube Conundrum') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/^Game (\d+): (.*)$/', $line, $matches);

                $sets = str($matches[2])
                    ->explode('; ')
                    ->map(function (string $set) {
                        return str($set)
                            ->explode(', ')
                            ->mapWithKeys(function (string $cubes) {
                                preg_match('/^(\d+) (.*)$/', $cubes, $matches);

                                return [$matches[2] => (int) $matches[1]];
                            });
                    });

                return [
                    'game' => (int) $matches[1],
                    'sets' => $sets,
                ];
            });
    }

    public function part1(): Part
    {
        $bag = [
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        ];

        $possibleGames = [];

        foreach ($this->input() as ['game' => $game, 'sets' => $sets]) {
            foreach ($bag as $color => $max) {
                $highest = $sets->pluck($color)->max();

                if ($highest > $max) {
                    continue 2;
                }
            }

            $possibleGames[] = $game;
        }

        return new Part(
            answer: array_sum($possibleGames),
        );
    }

    public function part2(): Part
    {
        $bag = ['red', 'green', 'blue'];

        $power = 0;

        foreach ($this->input() as ['game' => $game, 'sets' => $sets]) {
            $leastAmount = [];

            foreach ($bag as $color) {
                $leastAmount[$color] = max($sets->pluck($color)->max(), $leastAmount[$color] ?? 0);
            }

            $power += array_product($leastAmount);
        }

        return new Part(
            answer: $power,
        );
    }
};
