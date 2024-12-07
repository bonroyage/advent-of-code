<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXII\Day2\Result;
use MMXXII\Day2\Shape;

return new class('Rock Paper Scissors') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($instruction) => explode(' ', $instruction));
    }

    #[SampleAnswer(15)]
    public function part1(): int
    {
        return $this->input()->map(function ($guide) {
            $opponentWillPlay = match ($guide[0]) {
                'A' => Shape::Rock,
                'B' => Shape::Paper,
                'C' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction: '.$guide[0]),
            };

            $iShouldPlay = match ($guide[1]) {
                'X' => Shape::Rock,
                'Y' => Shape::Paper,
                'Z' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction: '.$guide[1]),
            };

            return $iShouldPlay->value + $iShouldPlay->result($opponentWillPlay)->value;
        })->sum();
    }

    #[SampleAnswer(12)]
    public function part2(): int
    {
        return $this->input()->map(function ($guide) {
            $opponentWillPlay = match ($guide[0]) {
                'A' => Shape::Rock,
                'B' => Shape::Paper,
                'C' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction:'.$guide[0]),
            };

            $result = match ($guide[1]) {
                'X' => Result::Lose,
                'Y' => Result::Draw,
                'Z' => Result::Win,
                default => throw new InvalidArgumentException('Invalid instruction: '.$guide[1]),
            };

            $iShouldPlay = match ($result) {
                Result::Win => match ($opponentWillPlay) {
                    Shape::Scissors => Shape::Rock,
                    Shape::Rock => Shape::Paper,
                    Shape::Paper => Shape::Scissors,
                },
                Result::Draw => $opponentWillPlay,
                Result::Lose => match ($opponentWillPlay) {
                    Shape::Rock => Shape::Scissors,
                    Shape::Paper => Shape::Rock,
                    Shape::Scissors => Shape::Paper,
                },
            };

            return $iShouldPlay->value + $result->value;
        })->sum();
    }
};
