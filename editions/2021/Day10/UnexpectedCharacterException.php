<?php

namespace MMXXI\Day10;

class UnexpectedCharacterException extends \Exception
{
    public function __construct(public readonly string $expect, public readonly string $got)
    {
        parent::__construct("Expected {$this->expect}, but found {$this->got} instead.");
    }
}
