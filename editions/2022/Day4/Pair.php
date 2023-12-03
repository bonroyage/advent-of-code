<?php

namespace MMXXII\Day4;

class Pair
{
    private readonly int $start1;
    private readonly int $end1;
    private readonly int $start2;
    private readonly int $end2;

    public function __construct(string $pair)
    {
        [$this->start1, $this->end1, $this->start2, $this->end2] = sscanf($pair, '%d-%d,%d-%d');
    }

    public function overlaps(): bool
    {
        return !empty(array_intersect(range($this->start1, $this->end1), range($this->start2, $this->end2)));
    }

    public function fullyOverlaps(): bool
    {
        return ($this->start2 >= $this->start1 && $this->end2 <= $this->end1)
            || ($this->start1 >= $this->start2 && $this->end1 <= $this->end2);
    }
}
