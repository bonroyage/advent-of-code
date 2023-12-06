<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Supply Stacks') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL.PHP_EOL);
    }

    private function buckets(): array
    {
        $lines = str($this->input()[0])
            ->explode(PHP_EOL)
            ->map(fn($line) => str_split($line))
            ->reverse()
            ->all();

        return collect(array_map(null, ...$lines))
            ->filter(fn($bucket) => is_numeric($bucket[0]))
            ->keyBy(fn($bucket) => $bucket[0])
            ->map(function (array $bucket) {
                return collect($bucket)
                    ->skip(1)
                    ->map(fn($item) => !empty(trim($item ?? '')) ? $item : null)
                    ->filter()
                    ->reverse()
                    ->values();
            })
            ->toArray();
    }

    private function movements(): Collection
    {
        return str($this->input()[1])
            ->explode(PHP_EOL)
            ->map(fn($movement) => sscanf($movement, 'move %d from %d to %d'));
    }

    public function part1(): Part
    {
        $buckets = $this->buckets();

        foreach ($this->movements() as [$count, $from, $to]) {
            $move = array_splice($buckets[$from], 0, $count);
            $move = array_reverse($move);
            $buckets[$to] = [...$move, ...$buckets[$to]];
        }

        $top = implode(array_map(fn($bucket) => $bucket[0], $buckets));

        return new Part(
            answer: $top,
        );
    }

    public function part2(): Part
    {
        $buckets = $this->buckets();

        foreach ($this->movements() as [$count, $from, $to]) {
            $move = array_splice($buckets[$from], 0, $count);
            $buckets[$to] = [...$move, ...$buckets[$to]];
        }

        $top = implode(array_map(fn($bucket) => $bucket[0], $buckets));

        return new Part(
            answer: $top,
        );
    }
};
