<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day8\SignalPatterns;

return new class ('Seven Segment Search') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn($line) => explode(' | ', $line));
    }


    public function part1(): Part
    {
        $digitsWithUniqueNumberOfSegments = 0;

        foreach ($this->input() as $line) {
            $outputs = explode(' ', $line[1]);

            foreach ($outputs as $output) {
                if (in_array(strlen($output), [2 /* 1 */, 3 /* 7 */, 4 /* 4 */, 7 /* 8 */], true)) {
                    $digitsWithUniqueNumberOfSegments++;
                }
            }
        }

        return new Part(
            question: 'In the output values, how many times do digits 1, 4, 7, or 8 appear?',
            answer: $digitsWithUniqueNumberOfSegments
        );
    }


    public function part2(): Part
    {
        $output = 0;

        foreach ($this->input() as $line) {
            $pattern = new SignalPatterns($line[0]);

            $output += (int)implode(array_map($pattern->getNumber(...), explode(' ', $line[1])));
        }

        return new Part(
            question: 'For each entry, determine all of the wire/segment connections and decode the four-digit output values. What do you get if you add up all of the output values?',
            answer: $output
        );
    }


    public function sort(string $in): string
    {
        $chars = str_split($in);
        sort($chars);
        return implode($chars);
    }

};
