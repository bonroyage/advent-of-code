<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Trebuchet?!') extends Day
{
    private const WORDS_TO_NUMBERS = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
    ];

    private function input(bool $wordsToNumbers): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) use ($wordsToNumbers) {
                if ($wordsToNumbers) {
                    $line = preg_replace_callback(
                        '/(?=('.implode('|', array_keys(self::WORDS_TO_NUMBERS)).'))/',
                        fn($matches) => self::WORDS_TO_NUMBERS[$matches[1]],
                        $line,
                    );
                }

                // Remove any non-numbers
                $line = preg_replace('/\D/', '', $line);

                return (int) $line[0].$line[-1];
            });
    }

    public function part1(): Part
    {
        return new Part(
            answer: $this->input(wordsToNumbers: false)->sum(),
        );
    }

    public function part2(): Part
    {
        return new Part(
            answer: $this->input(wordsToNumbers: true)->sum(),
        );
    }
};
