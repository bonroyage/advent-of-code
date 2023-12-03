<?php

namespace MMXXII\Day11;

use Closure;
use Illuminate\Support\Collection;

class Monkeys
{
    /**
     * @var Collection<\MMXXII\Day11\Monkey>
     */
    private Collection $monkeys;

    public function __construct()
    {
        $this->monkeys = collect([]);
    }

    public function addMonkey(int $id, array $startingItems, Closure $operation, int $divisible_by, int $trueId, int $falseId): void
    {
        $this->monkeys[$id] = new Monkey(
            $startingItems,
            $operation,
            $divisible_by,
            fn($isDivisible) => $isDivisible ? $this->monkeys[$trueId] : $this->monkeys[$falseId],
        );
    }

    public function run(int $rounds, Closure $keepManageable): void
    {
        for ($round = 1; $round <= $rounds; $round++) {
            $this->monkeys
                ->each(fn(Monkey $monkey) => $monkey->inspect($keepManageable));
        }
    }

    public function divisor(): int
    {
        $divisors = $this->monkeys
            ->map(fn(Monkey $monkey) => $monkey->divisible_by)
            ->toArray();

        return array_product($divisors);
    }

    public function worryLevelOfTopTwoMonkeys(): int
    {
        $levels = $this->monkeys
            ->map(fn(Monkey $monkey) => $monkey->inspectCounter)
            ->sortDesc()
            ->take(2)
            ->toArray();

        return array_product($levels);
    }
}
