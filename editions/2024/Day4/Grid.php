<?php

namespace MMXXIV\Day4;

class Grid
{
    public function __construct(
        public readonly array $grid,
    ) {}

    public function checkX(int $x, int $y): int
    {
        if ($this->grid[$y][$x] !== 'X') {
            return 0;
        }

        $count = 0;

        foreach (range(-1, 1) as $dy) {
            foreach (range(-1, 1) as $dx) {
                if ($this->value($y, $x, [$dy, $dx], 1) !== 'M') {
                    continue;
                }

                if ($this->value($y, $x, [$dy, $dx], 2) !== 'A') {
                    continue;
                }

                if ($this->value($y, $x, [$dy, $dx], 3) !== 'S') {
                    continue;
                }

                $count++;
            }
        }

        return $count;
    }

    public function checkA(int $x, int $y): bool
    {
        return $this->grid[$y][$x] === 'A'
            && $this->opposites($y, $x, 1)
            && $this->opposites($y, $x, -1);
    }

    private function value($y, $x, array $direction, int $steps): ?string
    {
        return $this->grid[$y + $direction[0] * $steps][$x + $direction[1] * $steps] ?? null;
    }

    private function opposites($y, $x, int $northDirection): ?string
    {
        $north = $this->grid[$y - 1][$x + $northDirection] ?? null;
        $south = $this->grid[$y + 1][$x + $northDirection * -1] ?? null;

        $pair = [$north, $south];

        return $pair === ['M', 'S'] || $pair === ['S', 'M'];
    }
}
