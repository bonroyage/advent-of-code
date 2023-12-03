<?php

namespace MMXXI\Day5;

class Line
{
    public readonly int $x1;
    public readonly int $y1;

    public readonly int $x2;
    public readonly int $y2;

    public function __construct(string $line)
    {
        [$this->x1, $this->y1, $this->x2, $this->y2] = sscanf($line, '%d,%d -> %d,%d');
    }

    public function direction(): LineDirection
    {
        if ($this->x1 === $this->x2) {
            return LineDirection::Horizontal;
        }

        if ($this->y1 === $this->y2) {
            return LineDirection::Vertical;
        }

        return LineDirection::Diagonal;
    }

    public function minX(): int
    {
        return min($this->x1, $this->x2);
    }

    public function maxX(): int
    {
        return max($this->x1, $this->x2);
    }

    public function minY(): int
    {
        return min($this->y1, $this->y2);
    }

    public function maxY(): int
    {
        return max($this->y1, $this->y2);
    }

    public function __toString(): string
    {
        return sprintf('%d,%d -> %d,%d', $this->x1, $this->y1, $this->x2, $this->y2);
    }
}
