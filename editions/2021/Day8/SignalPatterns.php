<?php

namespace MMXXI\Day8;

use Closure;
use Illuminate\Support\Collection;
use RuntimeException;

class SignalPatterns
{
    private array $numbers = [];

    private Collection $patterns;

    public function __construct(string $pattern)
    {
        $this->patterns = str($pattern)->explode(' ');

        /*
         * Digits with unique number of segments
         */

        // 1 is the only number with 2 segments
        $this->numbers[1] = $this->find(fn($wire) => strlen($wire) === 2);
        // remaining: 0, 2, 3, 4, 5, 6, 7, 8, 9

        // 4 is the only number with 4 segments
        $this->numbers[4] = $this->find(fn($wire) => strlen($wire) === 4);
        // remaining: 0, 2, 3, 5, 6, 7, 8, 9

        // 7 is the only number with 3 segments
        $this->numbers[7] = $this->find(fn($wire) => strlen($wire) === 3);
        // remaining: 0, 2, 3, 5, 6, 8, 9

        // 8 is the only number with 7 segments
        $this->numbers[8] = $this->find(fn($wire) => strlen($wire) === 7);
        // remaining: 0, 2, 3, 5, 6, 9

        /*
         * 6-segment digits
         */

        // 0 is a 6-segment digit and doesn't contain the top left and center segments
        $this->numbers[0] = $this->find(fn($wire) => strlen($wire) === 6 && !$this->contains($wire, $this->subtract($this->numbers[4], $this->numbers[1])));
        // remaining: 2, 3, 5, 6, 9

        // 6 is a 6-segment number that doesn't contain the two segments of the 1
        $this->numbers[6] = $this->find(fn($wire) => strlen($wire) === 6 && !$this->contains($wire, $this->numbers[1]));
        // remaining: 2, 3, 5, 9

        // 9 is the only number left with 6 segments
        $this->numbers[9] = $this->find(fn($wire) => strlen($wire) === 6);
        // remaining: 2, 3, 5

        /*
         * 5-segment digits
         */

        // 3 is the only remaining number that contains both segments of the 1
        $this->numbers[3] = $this->find(fn($wire) => $this->contains($wire, $this->numbers[1]));
        // remaining: 2, 5

        // 2 is the only remaining number that contains the bottom left corner
        $this->numbers[2] = $this->find(fn($wire) => $this->contains($wire, $this->subtract($this->numbers[6], $this->numbers[4])));
        // remaining: 5

        // 5 is the last option available
        $this->numbers[5] = $this->find();
    }

    private function find(Closure $rule = null): string
    {
        $filter = $this->patterns->filter(function ($wire) use ($rule) {
            if (in_array($wire, $this->numbers, true)) {
                return false;
            }

            return $rule ? $rule($wire) : true;
        });

        if ($filter->containsOneItem()) {
            return $filter->first();
        }

        throw new RuntimeException('More than 1 option available');
    }

    private function subtract(string $source, string $exclude): string
    {
        $source = str_split($source);
        $exclude = str_split($exclude);

        $data = array_filter($source, fn($letter) => !in_array($letter, $exclude, true));

        sort($data);

        return implode($data);
    }

    private function contains(string $source, string $needle): bool
    {
        $source = str_split($source);
        $needle = str_split($needle);

        return empty(array_diff($needle, $source));
    }

    private function sort(string $in): string
    {
        $chars = str_split($in);
        sort($chars);

        return implode($chars);
    }

    public function getNumbers(): array
    {
        return $this->numbers;
    }

    public function getNumber(string $signal): int
    {
        $signal = $this->sort($signal);

        foreach ($this->numbers as $number => $pattern) {
            if ($this->sort($pattern) === $signal) {
                return $number;
            }
        }

        throw new RuntimeException('Unknown signal');
    }
}
