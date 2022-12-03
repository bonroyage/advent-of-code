<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day2\Result;
use MMXXII\Day2\Shape;

return new class('Rock Paper Scissors') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile(PHP_EOL)
            ->map(fn($instruction) => explode(' ', $instruction));
    }


    public function part1(): Part
    {
        $sum = $this->input()->map(function ($guide) {
            $opponentWillPlay = match ($guide[0]) {
                'A' => Shape::Rock,
                'B' => Shape::Paper,
                'C' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction: ' . $guide[0])
            };

            $iShouldPlay = match ($guide[1]) {
                'X' => Shape::Rock,
                'Y' => Shape::Paper,
                'Z' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction: ' . $guide[1])
            };

            return $iShouldPlay->value + $iShouldPlay->result($opponentWillPlay)->value;
        })->sum();

        return new Part(
            question: 'What would your total score be if everything goes exactly according to your strategy guide?',
            answer: $sum
        );
    }


    public function part2(): Part
    {
        $sum = $this->input()->map(function ($guide) {
            $opponentWillPlay = match ($guide[0]) {
                'A' => Shape::Rock,
                'B' => Shape::Paper,
                'C' => Shape::Scissors,
                default => throw new InvalidArgumentException('Invalid instruction:' . $guide[0])
            };

            $result = match ($guide[1]) {
                'X' => Result::Lose,
                'Y' => Result::Draw,
                'Z' => Result::Win,
                default => throw new InvalidArgumentException('Invalid instruction: ' . $guide[1])
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

        return new Part(
            question: 'Following the Elf\'s instructions for the second column, what would your total score be if everything goes exactly according to your strategy guide?',
            answer: $sum
        );
    }

};
