<?php

use App\Solver\Day;
use App\Solver\Part;

return new class('Elves Look, Elves Say') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): string
    {
        return $this->getFile();
    }

    public function part1(): Part
    {
        $value = $this->input();
        $times = 40;

        for ($i = 1; $i <= $times; $i++) {
            $value = preg_replace_callback('/((\d)\2*)/', function ($match) {
                return strlen($match[0]).$match[2];
            }, $value);
        }

        return new Part(
            answer: strlen($value),
        );
    }

    public function part2(): Part
    {
        $value = $this->input();
        $times = 50;

        for ($i = 1; $i <= $times; $i++) {
            $value = preg_replace_callback('/((\d)\2*)/', function ($match) {
                return strlen($match[0]).$match[2];
            }, $value);
        }

        return new Part(
            answer: strlen($value),
        );
    }
};
