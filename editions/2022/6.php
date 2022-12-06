<?php

use App\Solver\Day;
use App\Solver\Part;

return new class ('Tuning Trouble') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function input(): array
    {
        return str_split($this->readFile()[0]);
    }


    public function part1(): Part
    {
        return new Part(
            question: 'How many characters need to be processed before the first start-of-packet marker is detected?',
            answer: $this->findMarker(4),
        );
    }


    public function part2(): Part
    {
        return new Part(
            question: 'How many characters need to be processed before the first start-of-message marker is detected?',
            answer: $this->findMarker(14),
        );
    }


    private function findMarker(int $uniqueLength): ?int
    {
        $input = $this->input();

        $input = array_map(null, ...array_map(fn($offset) => array_slice($input, $offset), range(0, $uniqueLength - 1)));

        foreach ($input as $index => $window) {
            if (array_unique($window) === $window) {
                return $index + $uniqueLength;
            }
        }

        return null;
    }

};
