<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class ('All in a Single Night') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/(\w+) to (\w+) = (\d+)/', $line, $matches);

                return [
                    'from' => $matches[1],
                    'to' => $matches[2],
                    'distance' => (int) $matches[3],
                ];
            });
    }

    public function part1(): Part
    {
        $distances = $this->input();

        $places = $distances
            ->flatMap(fn($route) => [
                $route['from'],
                $route['to'],
            ])
            ->unique()
            ->values();

        $shortest = PHP_INT_MAX;

        foreach ($this->permutate($places->all()) as $permutation) {
            $start = null;
            $dist = 0;

            foreach ($permutation as $dest) {
                if ($start !== null) {
                    $dist += $distances->first(function ($dist) use ($start, $dest) {
                        return ($dist['from'] === $start && $dist['to'] === $dest)
                            || ($dist['to'] === $start && $dist['from'] === $dest);
                    })['distance'];
                }

                $start = $dest;
            }

            if ($dist < $shortest) {
                $shortest = $dist;
            }
        }

        return new Part(
            answer: $shortest,
        );
    }

    public function part2(): Part
    {
        $distances = $this->input();

        $places = $distances
            ->flatMap(fn($route) => [
                $route['from'],
                $route['to'],
            ])
            ->unique()
            ->values();

        $longest = -1;

        foreach ($this->permutate($places->all()) as $permutation) {
            $start = null;
            $dist = 0;

            foreach ($permutation as $dest) {
                if ($start !== null) {
                    $dist += $distances->first(function ($dist) use ($start, $dest) {
                        return ($dist['from'] === $start && $dist['to'] === $dest)
                            || ($dist['to'] === $start && $dist['from'] === $dest);
                    })['distance'];
                }

                $start = $dest;
            }

            if ($dist > $longest) {
                $longest = $dist;
            }
        }

        return new Part(
            answer: $longest,
        );
    }

    private function permutate(array $elements): Generator
    {
        if (count($elements) <= 1) {
            yield $elements;
        } else {
            foreach ($this->permutate(array_slice($elements, 1)) as $permutation) {
                foreach (range(0, count($elements) - 1) as $i) {
                    yield array_merge(
                        array_slice($permutation, 0, $i),
                        [$elements[0]],
                        array_slice($permutation, $i),
                    );
                }
            }
        }
    }
};
