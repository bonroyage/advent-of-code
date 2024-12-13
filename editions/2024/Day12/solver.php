<?php

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\Coordinate;
use App\Utilities\Direction;
use Ds\PriorityQueue;
use Illuminate\Support\Collection;
use MMXXIV\Day12\Garden;

return new class ('Garden Groups') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(
        140,
        <<<'GARDEN'
            AAAA
            BBCD
            BBCC
            EEEC
            GARDEN
    )]
    #[SampleAnswer(
        772,
        <<<'GARDEN'
            OOOOO
            OXOXO
            OOOOO
            OXOXO
            OOOOO
            GARDEN
    )]
    #[SampleAnswer(
        1930,
        <<<'GARDEN'
            RRRRIICCFF
            RRRRIICCCF
            VVRRRCCFFF
            VVRCCCJFFF
            VVVVCJJCFE
            VVIVCCJJEE
            VVIIICJJEE
            MIIIIIJJEE
            MIIISIJEEE
            MMMISSJEEE
            GARDEN
    )]
    public function part1(): int
    {
        $garden = new Garden($this->input()->all());

        $index = 0;

        $frontier = new PriorityQueue();
        $frontier->push(new Coordinate(0, 0), 0);

        $explored = [];

        $cost = 0;

        while (!$frontier->isEmpty()) {
            $coordinate = $frontier->pop();

            if (isset($explored[serialize($coordinate)])) {
                continue;
            }

            $value = $garden->value($coordinate);

            $areaFrontier = new PriorityQueue();
            $areaFrontier->push($coordinate, 0);

            $area = 0;
            $perimeter = 0;

            while (!$areaFrontier->isEmpty()) {
                $coordinate = $areaFrontier->pop();

                if (isset($explored[serialize($coordinate)])) {
                    continue;
                }

                $explored[serialize($coordinate)] = $index;
                $area++;

                foreach (Direction::Orthogonal as $direction) {
                    $neighbour = $coordinate->moveInDirection($direction);

                    if (!$garden->isOnGrid($neighbour)) {
                        $perimeter++;
                    } elseif ($garden->value($neighbour) === $value) {
                        $areaFrontier->push($neighbour, 0);
                    } else {
                        $perimeter++;
                        $frontier->push($neighbour, 0);
                    }
                }
            }

            $cost += $area * $perimeter;
            $index++;
        }

        return $cost;
    }

    #[SampleAnswer(null)]
    public function part2()
    {
        throw new IncompleteException();
    }
};
