<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\AStar\AStar;
use Illuminate\Support\Collection;
use MMXXIV\Day10\ElevatedCoordinate;
use MMXXIV\Day10\Map;

return new class ('Hoof It') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line, int $y) => collect(str_split($line))
                ->map(fn(int $elevation, int $x) => new ElevatedCoordinate(
                    x: $x,
                    y: $y,
                    elevation: $elevation,
                )));
    }

    #[SampleAnswer(36)]
    public function part1(): int
    {
        $input = $this->input();

        $star = new AStar(
            new Map(
                $this->input()->all(),
            ),
        );

        $flattened = $input->flatten()->groupBy('elevation');

        return $flattened[0]
            ->crossJoin($flattened[9])
            ->filter(fn(array $pair) => $star->shortestPath($pair[0], $pair[1]) !== null)
            ->count();
    }

    #[SampleAnswer(81)]
    public function part2(): int
    {
        $input = $this->input();

        $star = new AStar(
            new Map(
                $this->input()->all(),
            ),
        );

        $flattened = $input->flatten()->groupBy('elevation');

        return $flattened[0]
            ->crossJoin($flattened[9])
            ->sum(fn(array $pair) => count($star->shortestPaths($pair[0], $pair[1]) ?? []));
    }
};
