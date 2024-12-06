<?php

namespace App\Solver;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class SampleAnswer
{
    public function __construct(
        public mixed $answer,
    ) {
    }
}
