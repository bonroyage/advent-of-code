<?php

namespace MMXXIV\Day11;

class StoneChanger
{
    private array $cache = [];

    public function countStones(array $stones, int $iterations)
    {
        $count = 0;

        foreach ($stones as $stone) {
            $count += $this->blink($stone, $iterations);
        }

        return $count;
    }

    private function blink(int $engraving, int $iterations): int
    {
        if ($this->hasCache($engraving, $iterations)) {
            return $this->getCache($engraving, $iterations);
        }

        if ($iterations === 0) {
            return 1;
        }

        if ($engraving === 0) {
            return $this->addToCacheAndReturn(
                $engraving,
                $iterations,
                $this->blink(1, $iterations - 1),
            );
        }

        $numberOfDigits = (int) floor(log10($engraving) + 1);

        if ($numberOfDigits % 2 === 0) {
            $split = pow(10, $numberOfDigits / 2);

            $leftSide = $this->addToCacheAndReturn(
                (int) ($engraving / $split),
                $iterations - 1,
                $this->blink((int) ($engraving / $split), $iterations - 1)
            );

            $rightSide = $this->addToCacheAndReturn(
                $engraving % $split,
                $iterations - 1,
                $this->blink($engraving % $split, $iterations - 1),
            );

            return $this->addToCacheAndReturn(
                $engraving,
                $iterations,
                $leftSide + $rightSide,
            );
        }

        return $this->addToCacheAndReturn(
            $engraving,
            $iterations,
            $this->blink($engraving * 2024, $iterations - 1),
        );
    }

    private function hasCache(int $engraving, int $iterations): bool
    {
        return isset($this->cache[$engraving][$iterations]);
    }

    private function getCache(int $engraving, int $iterations): int
    {
        return $this->cache[$engraving][$iterations];
    }

    private function addToCacheAndReturn(int $engraving, int $iterations, int $value): int
    {
        return $this->cache[$engraving][$iterations] = $value;
    }
}
