<?php

namespace MMXXII\Day11;

use Closure;

class Monkey
{
    public int $inspectCounter = 0;

    public function __construct(
        public array $items,
        public readonly Closure $operation,
        public readonly int $divisible_by,
        public readonly Closure $throw_to,
    ) {}

    private function receive(int $item): void
    {
        $this->items[] = $item;
    }

    public function inspect(Closure $keepManageable): void
    {
        foreach ($this->items as $i => $item) {
            $this->inspectCounter++;

            // Worry level changed
            $item = call_user_func($this->operation, $item);

            // Keep the level manageable
            $item = $keepManageable($item);

            // Check if current worry level satisfies the test
            $remainder = $item % $this->divisible_by;

            value($this->throw_to, $remainder === 0)->receive($item);

            unset($this->items[$i]);
        }
    }
}
