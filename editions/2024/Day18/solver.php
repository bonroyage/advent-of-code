<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\AStar\AStar;
use App\Utilities\Coordinate;
use Illuminate\Support\Collection;
use MMXXIV\Day18\Map;

return new class ('RAM Run') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(fn(string $line) => explode(',', $line));
    }

    #[SampleAnswer(22)]
    public function part1()
    {
        $input = $this->input();

        $size = $this->sample ? 7 : 71;
        $bytes = $this->sample ? 12 : 1024;

        $grid = array_fill(0, $size, array_fill(0, $size, '.'));

        for ($i = 0; $i < $bytes; $i++) {
            $coordinate = $input[$i];
            $grid[$coordinate[1]][$coordinate[0]] = '#';
        }

        $aStar = new AStar(new Map($grid));

        $result = $aStar->shortestPath(
            new Coordinate(0, 0),
            new Coordinate($size - 1, $size - 1),
        );

        return count($result->path()) - 1;
    }

    #[SampleAnswer('6,1')]
    public function part2(): string
    {
        $input = $this->input();

        $size = $this->sample ? 7 : 71;
        $bytes = $this->sample ? 12 : 1024;

        $grid = array_fill(0, $size, array_fill(0, $size, '.'));

        $map = new Map($grid);
        $aStar = new AStar($map);

        $start = new Coordinate(0, 0);
        $end = new Coordinate($size - 1, $size - 1);

        for ($i = 0; ; $i++) {
            $coordinate = $input[$i];
            $map->grid[$coordinate[1]][$coordinate[0]] = '#';

            if ($i < $bytes) {
                continue;
            }

            $newResult = $aStar->shortestPath($start, $end);

            if ($newResult === null) {
                return $coordinate[0] . ',' . $coordinate[1];
            }
        }

        throw new RuntimeException('No solution');
    }
};
