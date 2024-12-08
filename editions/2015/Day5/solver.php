<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class ('Doesn\'t He Have Intern-Elves For This?') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        $niceStrings = $this->input()->filter(function (string $line) {
            // It does not contain the strings ab, cd, pq, or xy/
            if (preg_match('/(ab|cd|pq|xy)/', $line) === 1) {
                return false;
            }

            // It contains at least three vowels (aeiou only)
            preg_match_all('/[aeoiu]/', $line, $vowels);

            if (count($vowels[0]) < 3) {
                return false;
            }

            // It contains at least one letter that appears twice in a row
            if (preg_match('/(\w)\1/', $line) !== 1) {
                return false;
            }

            return true;
        });

        return new Part(
            answer: $niceStrings->count(),
        );
    }

    public function part2(): Part
    {
        $niceStrings = $this->input()->filter(function (string $line) {
            // It contains a pair of any two letters that appears at least twice in the string without overlapping
            if (preg_match('/(\w\w).*\1/', $line) !== 1) {
                return false;
            }

            // It contains at least one letter which repeats with exactly one letter between them
            if (preg_match('/(\w)\w\1/', $line) !== 1) {
                return false;
            }

            return true;
        });

        return new Part(
            answer: $niceStrings->count(),
        );
    }
};
