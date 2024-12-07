<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Bridge Repair') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/^(\d+): (.*)$/', $line, $matches);

                return [
                    'target' => (int) $matches[1],
                    'values' => array_map(fn($v) => (int) $v, explode(' ', $matches[2])),
                ];
            });
    }

    #[SampleAnswer(3749)]
    public function part1(): int
    {
        $input = $this->input();

        return $input
            ->filter(fn(array $line) => $this->calculate(
                carry: $line['target'],
                values: $line['values'],
            ))
            ->sum('target');
    }

    #[SampleAnswer(11387)]
    public function part2(): int
    {
        $input = $this->input();

        return $input
            ->filter(fn(array $line) => $this->calculate(
                carry: $line['target'],
                values: $line['values'],
                withConcatenation: true,
            ))
            ->sum('target');
    }

    public function calculate(int $carry, array $values, bool $withConcatenation = false): bool
    {
        if ($values === []) {
            return $carry === 0;
        }

        $value = array_pop($values);

        /*
         * ADDITION
         *
         * We can only perform addition if the carry minus the value is at least
         * zero.
         */

        $subtracted = $carry - $value;

        if ($subtracted >= 0 && $this->calculate($subtracted, $values, $withConcatenation)) {
            return true;
        }

        /*
         * MULTIPLICATION
         *
         * We can only perform multiplication if the carry is divisible by the
         * value.
         */

        if ($carry % $value === 0 && $this->calculate($carry / $value, $values, $withConcatenation)) {
            return true;
        }

        /*
         * CONCATENATION
         *
         * We can definitely rule out concatenation if the value is not less than
         * the carry value.
         *
         * We can also rule it out if the carry value does not end with the value.
         */

        if (!$withConcatenation) {
            return false;
        }

        if ($value >= $carry) {
            return false;
        }

        $multiplier = pow(10, ceil(log10($value + 1)));

        if ($carry % $multiplier !== $value) {
            return false;
        }

        $split = ($carry - $value) / $multiplier;

        return $this->calculate($split, $values, true);
    }
};
