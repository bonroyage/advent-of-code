<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Parabolic Reflector Dish') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(136)]
    public function part1(): int
    {
        $input = $this->input();

        $tilted = $this->tiltNorth($input);

        return $this->sum($tilted);
    }

    #[SampleAnswer(64)]
    public function part2(): int
    {
        $tilted = $this->input()->toArray();

        $seenBefore = [
            $tilted,
        ];

        for ($i = 1; ; $i++) {
            $tilted = $this->spin($tilted);

            if (!in_array($tilted, $seenBefore, true)) {
                $seenBefore[] = $tilted;

                continue;
            }

            $offset = array_search($tilted, $seenBefore, true);
            $cycleLength = $i - $offset;

            break;
        }

        $remainder = (1_000_000_000 - $offset) % $cycleLength;

        return $this->sum(collect($seenBefore[$offset + $remainder]));
    }

    private function shiftLine(Collection|array $input, bool $reverse = false)
    {
        return collect($input)
            ->chunkWhile(fn(string $value) => $value !== '#')
            ->flatMap(function (Collection $chunk) use ($reverse) {
                $counts = $chunk->groupBy(fn($value) => $value);

                if ($reverse) {
                    return [
                        ...($counts['#'] ?? []),
                        ...($counts['.'] ?? []),
                        ...($counts['O'] ?? []),
                    ];
                }

                return [
                    ...($counts['#'] ?? []),
                    ...($counts['O'] ?? []),
                    ...($counts['.'] ?? []),
                ];
            })
            ->toArray();
    }

    private function spin(array $input): array
    {
        $columns = array_keys($input);

        /*
         * Tilt north
         */

        $tiltedNorth = [];
        foreach ($columns as $column) {
            $tiltedNorth[] = $this->shiftLine(array_column($input, $column));
        }

        /*
         * Tilt west (west is now north)
         */

        $tiltedWest = [];
        foreach ($columns as $column) {
            $tiltedWest[] = $this->shiftLine(array_column($tiltedNorth, $column));
        }

        /*
         * Tilt south
         */

        $tiltedSouth = [];
        foreach ($columns as $column) {
            $tiltedSouth[] = $this->shiftLine(array_column($tiltedWest, $column), true);
        }

        /*
         * Tilt east (west is now north)
         */

        $tiltedEast = [];
        foreach ($columns as $column) {
            $tiltedEast[] = $this->shiftLine(array_column($tiltedSouth, $column), true);
        }

        return $tiltedEast;
    }

    private function tiltNorth(Collection $input): Collection
    {
        $columns = $input->keys();

        $temp = [];

        foreach ($columns as $column) {
            $temp[] = $this->shiftLine($input->pluck($column));
        }

        return collect(array_map(null, ...$temp));
    }

    private function sum(Collection $input): int
    {
        $max = $input->keys()->count();

        return $input->keys()
            ->sum(function (int $column) use ($max, $input) {
                $sum = 0;

                $data = $input->pluck($column);

                foreach ($data as $i => $value) {
                    if ($value !== 'O') {
                        continue;
                    }

                    $sum += $max - $i;
                }

                return $sum;
            });
    }

    private function grid($input): string
    {
        $str = '';

        foreach ($input as $row) {
            $str .= implode('', $row).PHP_EOL;
        }

        return $str;
    }
};
