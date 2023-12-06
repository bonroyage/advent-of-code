<?php

namespace App\Solver;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use ReflectionClass;

abstract class Day
{
    public ?int $sample = null;

    public function __construct(
        public readonly string $title,
    ) {
    }

    abstract public function handle(): Generator;

    abstract public function part1(): Part;

    abstract public function part2(): Part;

    protected function getFile(string $file = null): Stringable
    {
        if (!isset($file)) {
            $childRef = new ReflectionClass(get_class($this));
            $file = $this->getFilePath($childRef->getFileName());
        }

        return str(file_get_contents($file))
            ->rtrim();
    }

    protected function getFileLines(string $explode = PHP_EOL, string $file = null): Collection
    {
        return $this->getFile($file)
            ->explode($explode);
    }

    private function getFilePath(string $pathToClass): string
    {
        if ($this->sample) {
            $partSpecificSampleFile = str_replace('.php', "-sample-{$this->sample}.txt", $pathToClass);

            if (file_exists($partSpecificSampleFile)) {
                return $partSpecificSampleFile;
            }

            return str_replace('.php', '-sample.txt', $pathToClass);
        }

        return str_replace('.php', '.txt', $pathToClass);
    }
}
