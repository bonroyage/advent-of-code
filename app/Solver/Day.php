<?php

namespace App\Solver;

use Generator;
use Illuminate\Support\Collection;

abstract class Day
{

    public bool $sample = false;


    public function __construct(public readonly string $title)
    {
    }


    abstract public function handle(): Generator;


    abstract public function part1(): Part;


    abstract public function part2(): Part;


    protected function readFile(string $explode = PHP_EOL, ?string $file = null): Collection
    {
        if (!isset($file)) {
            $childRef = new \ReflectionClass(get_class($this));

            $file = str_replace(
                search: '.php',
                replace: $this->sample ? '-sample.txt' : '.txt',
                subject: $childRef->getFileName()
            );
        }

        return str(file_get_contents($file))
            ->rtrim()
            ->explode($explode);
    }

}
