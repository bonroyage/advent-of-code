<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day8\SignalPatterns;

return new class('Seven Segment Search') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn($line) => explode(' | ', $line));
    }

    #[SampleAnswer(26)]
    public function part1(): int
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

        return $digitsWithUniqueNumberOfSegments;
    }

    #[SampleAnswer(61_229)]
    public function part2(): int
    {
        $output = 0;

        foreach ($this->input() as $line) {
            $pattern = new SignalPatterns($line[0]);

            $output += (int) implode(array_map($pattern->getNumber(...), explode(' ', $line[1])));
        }

        return $output;
    }

    public function sort(string $in): string
    {
        $chars = str_split($in);
        sort($chars);

        return implode($chars);
    }
};
