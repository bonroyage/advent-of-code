<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MMXXIII\Day12\LRUCache;

return new class('Hot Springs') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                [$string, $pattern] = explode(' ', $line);

                return [
                    $string,
                    array_map(fn($value) => (int) $value, explode(',', $pattern)),
                ];
            });
    }

    #[SampleAnswer(21)]
    public function part1(): int
    {
        return $this->input()->sum(function (array $line) {
            return $this->solve2(
                $line[0],
                $line[1],
            );
        });
    }

    #[SampleAnswer(525_152)]
    public function part2(): int
    {
        return $this->input()->sum(function (array $line) {
            $line[0] = implode('?', array_fill(0, 5, $line[0]));
            $line[1] = Arr::flatten(array_fill(0, 5, $line[1]));

            return $this->solve2(
                conditions: $line[0],
                groups: $line[1],
            );
        });
    }

    private function solve2(string $conditions, array $groups): int
    {
        if ($conditions === '') {
            return $groups === [] ? 1 : 0;
        }

        if ($groups === []) {
            return str_contains(haystack: $conditions, needle: '#') ? 0 : 1;
        }

        static $cache;

        $cache ??= new LRUCache(128);

        $key = serialize(func_get_args());

        if ($cache->has($key)) {
            return $cache->get($key);
        }

        $count = 0;

        $condition = $conditions[0];

        /*
         * If the current character is a '.' then we trim all '.' from the left
         * since these will all give the same recursive result.
         */
        if ($condition === '.') {
            $count += $this->solve2(
                conditions: ltrim($conditions, '.'),
                groups: $groups,
            );

            $cache->put($key, $count);

            return $count;
        }

        /*
         * Try to see a question mark as a dot.
         */
        if ($condition === '?') {
            $count += $this->solve2(
                conditions: substr($conditions, 1),
                groups: $groups,
            );
        }

        /*
         * We're not dealing with a '.' and have already covered the scenario
         * to replace a '?' with a '.'. At this point we only have to deal with
         * '#' and replacing a '?' with a '#'.
         *
         * Grab the first group size from the groups array.
         */
        $group = array_shift($groups);

        /*
         * The regex will fail in there's a dot in the next few characters (as
         * defined by the group), or the remaining string isn't long enough.
         *
         * Similarly, it will fail if the group is immediately followed by
         * a '#'. This is not possible, because it must be followed by a '.'
         * or a '?' (to be replaced by a '.')
         */
        if (preg_match('/^[#|?]{'.$group.'}(?:[.|?](?<remainder>.*))?$/', $conditions, $matches)) {
            $count += $this->solve2(
                conditions: $matches['remainder'] ?? '',
                groups: $groups,
            );
        }

        $cache->put($key, $count);

        return $count;
    }
};
