<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Trash Compactor') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(4277556)]
    public function part1()
    {
        $transposed = array_map(null, ...$this->input()->map(fn($line) => str($line)->squish()->explode(' ')->toArray())->all());

        $sum = 0;

        foreach ($transposed as $problem) {
            $operator = array_pop($problem);

            $sum += match ($operator) {
                '+' => array_sum($problem),
                '*' => array_product($problem),
            };
        }

        return $sum;
    }

    #[SampleAnswer(3263827)]
    public function part2()
    {
        $lines = $this->input()->map(fn($line) => str_split($line));
        $transposed = array_map(null, ...$lines);

        $sum = 0;
        $operator = null;
        $problem = [];

        foreach ($transposed as $line) {
            if (array_all($line, fn($char) => $char === ' ')) {
                $sum += match ($operator) {
                    '+' => array_sum($problem),
                    '*' => array_product($problem),
                };

                $problem = [];

                continue;
            }

            $newOperator = array_pop($line);

            if ($newOperator === '+' || $newOperator === '*') {
                $operator = $newOperator;
            }

            $line = array_filter($line, fn($char) => $char !== ' ');
            $problem[] = implode($line);
        }

        $sum += match ($operator) {
            '+' => array_sum($problem),
            '*' => array_product($problem),
        };

        return $sum;
    }
};
