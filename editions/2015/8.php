<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Matchsticks') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        $lines = $this->input()->map(function (string $line) {
            $stringLiteralLength = strlen($line);

            $line = trim($line, '"');
            $line = preg_replace('/(\\\\\\\\)/', '.', $line);
            $line = preg_replace('/(\\\\")/', '.', $line);
            $line = preg_replace('/(\\\\x\w{2})/', '.', $line);

            $memoryLength = strlen($line);

            return $stringLiteralLength - $memoryLength;
        });

        return new Part(
            answer: $lines->sum(),
        );
    }

    public function part2(): Part
    {
        $lines = $this->input()->map(function (string $line) {
            $stringLiteralLength = strlen($line);
            $line = addslashes($line);

            $encodedLength = strlen($line) + 2;

            return $encodedLength - $stringLiteralLength;
        });

        return new Part(
            answer: $lines->sum(),
        );
    }
};
