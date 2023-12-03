<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day10\Line;

return new class('Syntax Scoring') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile();
    }

    public function part1(): Part
    {
        $scores = [];

        foreach ($this->input() as $line) {
            try {
                new Line($line);
            } catch (\MMXXI\Day10\UnexpectedCharacterException $e) {
                $scores[] = match ($e->got) {
                    ')' => 3,
                    ']' => 57,
                    '}' => 1197,
                    '>' => 25137,
                };
            }
        }

        return new Part(
            question: 'Find the first illegal character in each corrupted line of the navigation subsystem. What is the total syntax error score for those errors?',
            answer: array_sum($scores),
        );
    }

    public function part2(): Part
    {
        $scores = [];

        foreach ($this->input() as $line) {
            try {
                $line = new Line($line);
                $score = 0;

                foreach (array_reverse($line->unclosed) as $unclosed) {
                    $score *= 5;
                    $score += match ($unclosed) {
                        '(' => 1,
                        '[' => 2,
                        '{' => 3,
                        '<' => 4,
                    };
                }

                $scores[] = $score;
            } catch (\MMXXI\Day10\UnexpectedCharacterException) {
            }
        }

        sort($scores);

        return new Part(
            question: 'Find the completion string for each incomplete line, score the completion strings, and sort the scores. What is the middle score?',
            answer: $scores[floor((count($scores) - 1) / 2)],
        );
    }
};
