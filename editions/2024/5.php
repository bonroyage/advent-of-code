<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Print Queue') extends Day
{
    private function pageOrderingRules(): Collection
    {
        return str($this->getFileLines(PHP_EOL.PHP_EOL)[0])
            ->explode(PHP_EOL)
            ->map(fn(string $line) => explode('|', $line));
    }

    private function updates(): Collection
    {
        return str($this->getFileLines(PHP_EOL.PHP_EOL)[1])
            ->explode(PHP_EOL)
            ->map(fn(string $line) => explode(',', $line));
    }

    public function part1(): Part
    {
        $rules = $this->pageOrderingRules();

        $value = $this->updates()
            ->sum(function ($update) use ($rules) {
                $matchingRules = $rules->filter(fn($rule) => count(array_intersect($update, $rule)) === 2);

                foreach ($matchingRules as [$first, $second]) {
                    if (array_search($first, $update) > array_search($second, $update)) {
                        return 0;
                    }
                }

                $length = count($update);
                $middle = ($length - 1) / 2;

                return $update[$middle];
            });

        return new Part(
            answer: $value,
        );
    }

    public function part2(): Part
    {
        $rules = $this->pageOrderingRules();

        $value = $this->updates()
            ->filter(function ($update) use ($rules) {
                $matchingRules = $rules->filter(fn($rule) => count(array_intersect($update, $rule)) === 2);

                foreach ($matchingRules as [$first, $second]) {
                    if (array_search($first, $update) > array_search($second, $update)) {
                        return true;
                    }
                }

                return false;
            })
            ->sum(function ($update) use ($rules) {
                usort($update, function ($a, $b) use ($rules) {
                    foreach ($rules as [$left, $right]) {
                        if ($left !== $a && $right !== $a) {
                            continue;
                        }

                        if ($left !== $b && $right !== $b) {
                            continue;
                        }

                        return $left === $a ? -1 : 1;
                    }

                    return 0;
                });

                $length = count($update);
                $middle = ($length - 1) / 2;

                return $update[$middle];
            });

        return new Part(
            answer: $value,
        );
    }
};
