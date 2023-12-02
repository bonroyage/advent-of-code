<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

return new class ('Trebuchet?!') extends Day
{

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

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
        return $this->readFile()
            ->map(function (string $line) use ($wordsToNumbers) {
                if ($wordsToNumbers) {
                    $keys = '/(\d|'.implode('|', array_keys(self::WORDS_TO_NUMBERS)).')/';
                    preg_match($keys, $line, $matches);
                    $firstNumber = self::WORDS_TO_NUMBERS[$matches[0]] ?? $matches[0];

                    $reverseKeys = '/(\d|'.Str::reverse(implode('|', array_keys(self::WORDS_TO_NUMBERS))).')/';
                    preg_match($reverseKeys, Str::reverse($line), $matches);
                    $lastNumber = self::WORDS_TO_NUMBERS[Str::reverse($matches[0])] ?? $matches[0];

                    return (int) $firstNumber.$lastNumber;
                }

                $numbers = preg_replace(
                    '/\D/',
                    '',
                    $line
                );

                return (int) (substr($numbers, 0, 1).substr($numbers, -1));
            });
    }


    public function part1(): Part
    {
        return new Part(
            question: 'What is the sum of all of the calibration values?',
            answer: $this->input(wordsToNumbers: false)->sum(),
        );
    }


    public function part2(): Part
    {
        return new Part(
            question: 'What is the sum of all of the calibration values?',
            answer: $this->input(wordsToNumbers: true)->sum(),
        );
    }

};
