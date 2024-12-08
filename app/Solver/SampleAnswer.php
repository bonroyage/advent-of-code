<?php

namespace App\Solver;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class SampleAnswer
{
    public int $part;

    public function __construct(
        public mixed $answer,
        public ?string $input = null,
    ) {}
}
