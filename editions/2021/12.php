<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Passage Pathing') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $row) => explode('-', $row));
    }

    public function part1(): Part
    {
        $input = $this->input();

        $destinations = $input
            ->merge($input->map(fn($path) => array_reverse($path)))
            ->groupBy(0)
            ->map(fn(Collection $collection) => $collection->pluck(1));

        $paths = $this->followPath($destinations, [], 'start', false);

        return new Part(
            answer: count($paths),
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $destinations = $input
            ->merge($input->map(fn($path) => array_reverse($path)))
            ->groupBy(0)
            ->map(fn(Collection $collection) => $collection->pluck(1));

        $paths = $this->followPath($destinations, [], 'start');

        return new Part(
            answer: count($paths),
        );
    }

    private function followPath(Collection $destinations, array $path, string $key, bool $canVisitSmallCaveTwice = true): array
    {
        $paths = [];
        $path = [...$path, $key];

        foreach ($destinations->get($key) as $next) {
            if (ctype_lower($next)) {
                if ($canVisitSmallCaveTwice) {
                    // Can visit small cave twice, and has already visited this cave twice
                    if ((array_count_values($path)[$next] ?? 0) === 2) {
                        continue;
                    }
                } else {
                    // Cannot visit small cave twice, and it has already visited this cave
                    if (in_array($next, $path, true)) {
                        continue;
                    }
                }
            }

            if ($next === 'start') {
                continue;
            } else if ($next === 'end') {
                $paths[] = [...$path, $next];

                continue;
            }

            $result = $this->followPath($destinations, $path, $next, $canVisitSmallCaveTwice && !(ctype_lower($next) && in_array($next, $path, true)));

            $paths = array_merge($paths, $result);
        }

        return $paths;
    }
};
