<?php

namespace App\Solver;

use Generator;
use Illuminate\Support\Collection;

abstract class Day
{
    public ?int $sample = null;

    public function __construct(public readonly string $title)
    {
    }

    abstract public function handle(): Generator;

    abstract public function part1(): Part;

    abstract public function part2(): Part;

    protected function readFile(string $explode = PHP_EOL, string $file = null): Collection
    {
        if (!isset($file)) {
            $childRef = new \ReflectionClass(get_class($this));

            if ($this->sample) {
                $partSpecificSampleFile = str_replace('.php', "-sample-{$this->sample}.txt", $childRef->getFileName());

                if (file_exists($partSpecificSampleFile)) {
                    $file = $partSpecificSampleFile;
                } else {
                    $file = str_replace('.php', '-sample.txt', $childRef->getFileName());
                }
            } else {
                $file = str_replace('.php', '.txt', $childRef->getFileName());
            }
        }

        return str(file_get_contents($file))
            ->rtrim()
            ->explode($explode);
    }
}
