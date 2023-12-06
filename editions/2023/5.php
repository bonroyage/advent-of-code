<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

return new class('If You Give A Seed A Fertilizer') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): array
    {
        $input = $this->getFileLines();

        /*
         * Get all the seeds from the first line
         */

        preg_match_all('/(\d+)/', $input[0], $matches);
        $seeds = $matches[1];

        /*
         * Parse all the maps on the subsequent lines
         */

        preg_match_all(
            '/(?:(?<name>\w+-to-\w+) map:\n(?<ranges>(?:\d+\s?)+))+/',
            $input->implode(PHP_EOL),
            $matches,
            PREG_SET_ORDER,
        );

        $maps = Arr::mapWithKeys($matches, function (array $match) {
            preg_match_all(
                '/(?<dest>\d+) (?<source>\d+) (?<length>\d+)/',
                $match['ranges'],
                $matches,
                PREG_SET_ORDER,
            );

            return [
                $match['name'] => Arr::map($matches, fn(array $map) => [
                    'destination range start' => (int) $map['dest'],
                    'source range start' => (int) $map['source'],
                    'range length' => (int) $map['length'],
                ]),
            ];
        });

        return [
            'seeds' => $seeds,
            'maps' => $maps,
        ];
    }

    public function part1(): Part
    {
        $input = $this->input();

        $seeds = Arr::map($input['seeds'], fn($seed) => [
            'start' => (int) $seed,
            'end' => (int) $seed,
        ]);

        $seeds = $this->map(
            $seeds,
            ...$input['maps'],
        );

        return new Part(
            answer: min(Arr::flatten($seeds)),
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $seeds = Arr::map(array_chunk($input['seeds'], 2), fn($seed) => [
            'start' => (int) $seed[0],
            'end' => $seed[0] + $seed[1] - 1,
        ]);

        $seeds = $this->map(
            $seeds,
            ...$input['maps'],
        );

        return new Part(
            answer: min(Arr::flatten($seeds)),
        );
    }

    private function map(array $seeds, array ...$maps): array
    {
        foreach ($maps as $map) {
            /*
             * Recalculate the ranges to start and end, instead of start and length.
             */

            /** @var Collection $ranges */
            $ranges = collect($map)
                ->map(fn($value) => [
                    'source range start' => $value['source range start'],
                    'source range end' => $value['source range start'] + $value['range length'] - 1,
                    'difference' => $value['destination range start'] - $value['source range start'],
                ])
                ->sortBy('source range start')
                ->values();

            /*
             * If there's no range that starts at 0, add it because any value that
             * is not explicitly mapped, is mapped to the same value as the source.
             */

            if ($ranges[0]['source range start'] !== 0) {
                $ranges->prepend([
                    'source range start' => 0,
                    'source range end' => $ranges[0]['source range start'] - 1,
                    'difference' => 0,
                ]);
            }

            /*
             * Do the same for the end all the way up to infinity.
             */

            $ranges->push([
                'source range start' => $ranges->last()['source range end'] + 1,
                'source range end' => INF,
                'difference' => 0,
            ]);

            /*
             * Run through the input seeds. It's possible that the ranges of this
             * map change the start and end value. At the end of this process, the
             * number of 'seeds' should remain the same, while the number of ranges
             * can have increased.
             */

            $newSeeds = [];

            foreach ($seeds as $seed) {
                foreach ($ranges as $range) {
                    /*
                     * If the seeds are outside the boundary of the ranges of the map,
                     * then we can safely ignore this range for this iteration. It will
                     * be picked up in whole by another range, or divided over multiple
                     * other ranges.
                     */

                    if ($seed['end'] < $range['source range start'] || $seed['start'] > $range['source range end']) {
                        continue;
                    }

                    /*
                     * We pick the highest start value and the smallest end value,
                     * so our new seed range is as small as possible. In case the
                     * seed's start or seed's end are lower than the source range start
                     * or higher than the source range end respectively, these chunks
                     * will have been picked up by another range.
                     *
                     * The difference between the source range start and destination
                     * range start is also added here, so the new seeds are in essence
                     * the input values for the next map iteration.
                     */

                    $newSeeds[] = [
                        'start' => max($seed['start'], $range['source range start']) + $range['difference'],
                        'end' => min($seed['end'], $range['source range end']) + $range['difference'],
                    ];
                }
            }

            /*
             * Put the new seeds back onto the $seeds for the next map
             */

            $seeds = $newSeeds;
        }

        return $seeds;
    }
};
