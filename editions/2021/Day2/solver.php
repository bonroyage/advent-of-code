<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Dive!') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($instruction) => explode(' ', $instruction));
    }

    #[SampleAnswer(150)]
    public function part1(): int
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

        return $horizontal * $depth;
    }

    #[SampleAnswer(900)]
    public function part2(): int
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

        return $horizontal * $depth;
    }
};
