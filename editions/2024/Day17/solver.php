<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use MMXXIV\Day17\Registers;

return new class ('Chronospatial Computer') extends Day {
    private function registerA(): int
    {
        preg_match('/^Register A: (\d+)/', $this->getFile(), $matches);

        return (int) $matches[1];
    }

    private function instructions(): array
    {
        preg_match('/Program: (.*)/', $this->getFile(), $matches);

        return str($matches[1])
            ->explode(',')
            ->map(fn($value) => (int) $value)
            ->all();
    }

    #[SampleAnswer(
        '4,6,3,5,6,3,5,2,1,0',
        <<<'INPUT'
            Register A: 729
            Register B: 0
            Register C: 0

            Program: 0,1,5,4,3,0
            INPUT
    )]
    public function part1(): string
    {
        $a = $this->registerA();
        $instructions = $this->instructions();

        $registers = new Registers($a);

        return $registers->process($instructions);
    }

    #[SampleAnswer(
        117440,
        <<<'INPUT'
            Register A: 2024
            Register B: 0
            Register C: 0

            Program: 0,3,5,4,3,0
            INPUT
    )]
    public function part2(): int
    {
        $instructions = $this->instructions();
        $expected = implode(',', $instructions);

        $queue = [0];

        while ($queue !== []) {
            $start = array_shift($queue) * 8;

            for ($i = $start; $i < $start + 8; $i++) {
                $registers = new Registers($i);
                $output = $registers->process($instructions);

                if ($expected === $output) {
                    return $i;
                }

                if (str_ends_with($expected, $output)) {
                    $queue[] = $i;
                }
            }
        }

        throw new RuntimeException('No solution found');
    }
};
