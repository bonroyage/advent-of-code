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
                    'values' => explode(' ', $matches[2]),
                ];
            });
    }

    #[SampleAnswer(3749)]
    public function part1(): int
    {
        $input = $this->input();

        $values = $input
            ->filter(function (array $line) {
                return $this->calculate(
                    carry: array_shift($line['values']),
                    values: $line['values'],
                    target: $line['target'],
                    operations: static function (int $carry, int $value) {
                        yield $carry + $value;
                        yield $carry * $value;
                    },
                );
            });

        return $values->sum('target');
    }

    #[SampleAnswer(11387)]
    public function part2(): int
    {
        $input = $this->input();

        $values = $input
            ->filter(function (array $line) {
                return $this->calculate(
                    carry: array_shift($line['values']),
                    values: $line['values'],
                    target: $line['target'],
                    operations: static function (int $carry, int $value) {
                        yield $carry + $value;
                        yield $carry * $value;
                        yield (int) "{$carry}{$value}";
                    },
                );
            });

        return $values->sum('target');
    }

    public function calculate(int $carry, array $values, int $target, Closure $operations): bool
    {
        if ($values === []) {
            return $carry === $target;
        }

        $value = array_shift($values);

        foreach ($operations($carry, $value) as $calculatedOperation) {
            if ($calculatedOperation > $target) {
                continue;
            }

            if ($this->calculate($calculatedOperation, $values, $target, $operations)) {
                return true;
            }
        }

        return false;
    }
};
