<?php

use App\Solver\Day;
use App\Solver\Part;
use MMXXII\Day11\Monkeys;

return new class('Monkey in the Middle') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Monkeys
    {
        $monkeys = new Monkeys();

        $inputs = $this->getFileLines(PHP_EOL.PHP_EOL);

        foreach ($inputs as $monkey) {
            preg_match('/^Monkey (\d+):\n/m', $monkey, $index);
            preg_match('/^\s\sStarting items: ([\d,\s]+)\n/m', $monkey, $startingItems);
            preg_match('/^\s\sOperation: new = old ([+\-*\/]) (old|\d+)\n/m', $monkey, $operation);
            preg_match('/^\s\sTest: divisible by (\d+)\n/m', $monkey, $test);
            preg_match('/^\s\s\s\sIf true: throw to monkey (\d+)\n/m', $monkey, $true);
            preg_match('/^\s\s\s\sIf false: throw to monkey (\d+)/m', $monkey, $false);

            $monkeys->addMonkey(
                $index[1],
                explode(', ', $startingItems[1]),
                function (int $old) use ($operation) {
                    $value = $operation[2] === 'old' ? $old : $operation[2];

                    return match ($operation[1]) {
                        '+' => $old + $value,
                        '*' => $old * $value,
                        default => throw new InvalidArgumentException('Unknown operator: '.$operation[1]),
                    };
                },
                $test[1],
                $true[1],
                $false[1],
            );
        }

        return $monkeys;
    }

    public function part1(): Part
    {
        $monkeys = $this->input();

        $monkeys->run(20, fn($item) => floor($item / 3));

        return new Part(
            answer: $monkeys->worryLevelOfTopTwoMonkeys(),
        );
    }

    public function part2(): Part
    {
        $monkeys = $this->input();

        $commonMod = $monkeys->divisor();
        $monkeys->run(10_000, fn($item) => $item % $commonMod);

        return new Part(
            answer: $monkeys->worryLevelOfTopTwoMonkeys(),
        );
    }
};
