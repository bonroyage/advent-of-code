<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day10\Line;
use MMXXI\Day10\UnexpectedCharacterException;

return new class('Syntax Scoring') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(26_397)]
    public function part1(): int
    {
        $scores = [];

        foreach ($this->input() as $line) {
            try {
                new Line($line);
            } catch (UnexpectedCharacterException $e) {
                $scores[] = match ($e->got) {
                    ')' => 3,
                    ']' => 57,
                    '}' => 1197,
                    '>' => 25137,
                };
            }
        }

        return array_sum($scores);
    }

    #[SampleAnswer(288_957)]
    public function part2(): int
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
            } catch (UnexpectedCharacterException) {
            }
        }

        sort($scores);

        return $scores[floor((count($scores) - 1) / 2)];
    }
};
