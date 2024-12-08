<?php

namespace App\Solver;

use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use ReflectionClass;

abstract class Day
{
    public ?SampleAnswer $sample = null;

    public function __construct(
        public readonly string $title,
    ) {}

    abstract public function part1();

    abstract public function part2();

    protected function getFile(?string $file = null): Stringable
    {
        if (!isset($file)) {
            $childRef = new ReflectionClass(get_class($this));
            $file = $this->getFilePath($childRef->getFileName());
        }

        if ($this->sample?->input) {
            return str($this->sample->input)
                ->rtrim();
        }

        return str(file_get_contents($file))
            ->rtrim();
    }

    protected function getFileLines(string $explode = PHP_EOL, ?string $file = null): Collection
    {
        return $this->getFile($file)
            ->explode($explode);
    }

    private function getFilePath(string $pathToClass): string
    {
        if ($this->sample) {
            $partSpecificSampleFile = dirname($pathToClass) . "/sample.{$this->sample->part}.txt";

            if (file_exists($partSpecificSampleFile)) {
                return $partSpecificSampleFile;
            }

            return dirname($pathToClass) . '/sample.txt';
        }

        return dirname($pathToClass) . '/real.txt';
    }
}
