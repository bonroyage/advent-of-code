<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Laboratories') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(21)]
    public function part1()
    {
        $lines = $this->input();
        $beams = [array_search('S', $lines->shift(), true) => true];
        $splits = 0;

        foreach ($lines as $line) {
            foreach (array_keys($beams) as $beam) {
                if ($line[$beam] !== '^') {
                    continue;
                }

                unset($beams[$beam]);

                $splits++;

                if (isset($line[$beam - 1])) {
                    $beams[$beam - 1] = true;
                }

                if (isset($line[$beam + 1])) {
                    $beams[$beam + 1] = true;
                }
            }
        }

        return $splits;
    }

    #[SampleAnswer(40)]
    public function part2()
    {
        $lines = $this->input();
        $timeline = array_map(fn(string $char) => $char === 'S' ? 1 : 0, $lines[0]);

        foreach ($lines as $line) {
            foreach ($timeline as $beam => &$paths) {
                if ($paths === 0 || $line[$beam] !== '^') {
                    continue;
                }

                if (isset($timeline[$beam - 1])) {
                    $timeline[$beam - 1] += $paths;
                }

                if (isset($timeline[$beam + 1])) {
                    $timeline[$beam + 1] += $paths;
                }

                $paths = 0;
            }
        }

        return array_sum($timeline);
    }
};
