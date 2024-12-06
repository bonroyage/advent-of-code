<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Lens Library') extends Day
{
    private function input(): Collection
    {
        return $this->getFile()->explode(',');
    }

    #[SampleAnswer(1_320)]
    public function part1(): int
    {
        return $this->input()
            ->sum(fn(string $hash) => $this->hash($hash));
    }

    #[SampleAnswer(145)]
    public function part2(): int
    {
        $boxes = $this->input()
            ->reduce(function (array $carry, string $hash) {
                preg_match('/^(.*)([-=])(\d+)?$/', $hash, $matches);

                $box = $this->hash($matches[1]);
                $operation = $matches[2];
                $focal = $matches[3] ?? null;

                if ($operation === '=') {
                    $carry[$box][$matches[1]] = $focal;
                }

                if ($operation === '-') {
                    unset($carry[$box][$matches[1]]);
                }

                return $carry;
            }, []);

        $answer = 0;

        foreach ($boxes as $box => $contents) {
            foreach (array_values($contents) as $slot => $focalLength) {
                $answer += ($box + 1) * ($slot + 1) * $focalLength;
            }
        }

        return $answer;
    }

    private function hash(string $input): int
    {
        $value = 0;

        foreach (str_split($input) as $char) {
            $value += ord($char);
            $value *= 17;
            $value = $value % 256;
        }

        return $value;
    }
};
