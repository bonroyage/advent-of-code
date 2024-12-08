<?php

namespace MMXXII\Day7;

class File implements Node
{
    public function __construct(
        public readonly string $name,
        public readonly int $size,
        public readonly Directory $parent,
    ) {}

    public function size(): int
    {
        return $this->size;
    }
}
