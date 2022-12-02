<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class ('Dive!') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile(__DIR__ . '/2.txt')
            ->map(fn($instruction) => explode(' ', $instruction));
    }


    private function part1(): Part
    {
        $input = $this->input();

        $horizontal = 0;
        $depth = 0;

        foreach ($input as [$action, $movement]) {
            if ($action === 'forward') {
                $horizontal += $movement;
            } else if ($action === 'up') {
                $depth -= $movement;
            } else if ($action === 'down') {
                $depth += $movement;
            }
        }

        return new Part(
            question: 'Calculate the horizontal position and depth you would have after following the planned course. What do you get if you multiply your final horizontal position by your final depth?',
            answer: $horizontal * $depth
        );
    }


    private function part2(): Part
    {
        $input = $this->input();

        $horizontal = 0;
        $depth = 0;
        $aim = 0;

        foreach ($input as [$action, $movement]) {
            if ($action === 'forward') {
                $horizontal += $movement;
                $depth += $movement * $aim;
            } else if ($action === 'up') {
                $aim -= $movement;
            } else if ($action === 'down') {
                $aim += $movement;
            }
        }

        return new Part(
            question: 'Using this new interpretation of the commands, calculate the horizontal position and depth you would have after following the planned course. What do you get if you multiply your final horizontal position by your final depth?',
            answer: $horizontal * $depth
        );
    }

};
