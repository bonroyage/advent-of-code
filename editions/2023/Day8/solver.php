<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Haunted Wasteland') extends Day {
    private function instructions(): array
    {
        return str_split($this->getFileLines()->first());
    }

    private function network(): Collection
    {
        return $this->getFileLines()->skip(2)->mapWithKeys(function (string $line) {
            preg_match('/(\w+) = \((\w+), (\w+)\)/', $line, $matches);

            return [
                $matches[1] => [
                    'L' => $matches[2],
                    'R' => $matches[3],
                ],
            ];
        });
    }

    #[SampleAnswer(2)]
    public function part1(): int
    {
        $node = 'AAA';
        $instructions = $this->instructions();
        $network = $this->network();

        for ($step = 0; $node !== 'ZZZ'; $step++) {
            $i = $step % count($instructions);
            $instruction = $instructions[$i];
            $node = $network[$node][$instruction];
        }

        return $step;
    }

    #[SampleAnswer('6')]
    public function part2(): string
    {
        $instructions = $this->instructions();
        $network = $this->network();

        $lcm = $network->keys()
            ->filter(fn(string $node) => str_ends_with($node, 'A'))
            ->reduce(function (GMP $carry, string $node) use ($network, $instructions) {
                for ($step = 0; !str_ends_with($node, 'Z'); $step++) {
                    $i = $step % count($instructions);
                    $instruction = $instructions[$i];
                    $node = $network[$node][$instruction];
                }

                return gmp_lcm($carry, $step);
            }, new GMP(1));

        return (string) $lcm;
    }
};
