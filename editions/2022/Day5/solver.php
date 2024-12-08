<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Supply Stacks') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL . PHP_EOL);
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

    #[SampleAnswer('CMZ')]
    public function part1(): string
    {
        $buckets = $this->buckets();

        foreach ($this->movements() as [$count, $from, $to]) {
            $move = array_splice($buckets[$from], 0, $count);
            $move = array_reverse($move);
            $buckets[$to] = [...$move, ...$buckets[$to]];
        }

        return implode(array_map(fn($bucket) => $bucket[0], $buckets));
    }

    #[SampleAnswer('MCD')]
    public function part2(): string
    {
        $buckets = $this->buckets();

        foreach ($this->movements() as [$count, $from, $to]) {
            $move = array_splice($buckets[$from], 0, $count);
            $buckets[$to] = [...$move, ...$buckets[$to]];
        }

        return implode(array_map(fn($bucket) => $bucket[0], $buckets));
    }
};
