<?php

namespace App\Solver;

use Generator;
use Illuminate\Support\Collection;

abstract class Day
{

    public function __construct(public readonly string $title)
    {
    }


    abstract public function handle(): Generator;


    protected function readFile(string $file, string $explode = PHP_EOL): Collection
    {
        return str(file_get_contents($file))
            ->trim()
            ->explode($explode);
    }

}
