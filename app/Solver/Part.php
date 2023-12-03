<?php

namespace App\Solver;

class Part
{
    public function __construct(public readonly string $question, public readonly mixed $answer)
    {
    }
}
