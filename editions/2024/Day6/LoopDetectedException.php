<?php

namespace MMXXIV\Day6;

class LoopDetectedException extends \RuntimeException
{
    public function __construct(
        public readonly array $movements,
        public readonly Movement $attemptedMovement,
    ) {
        parent::__construct('Loop detected.');
    }
}
