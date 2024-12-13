<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\Coordinate;
use App\Utilities\Direction;
use Ds\PriorityQueue;
use Illuminate\Support\Collection;
use MMXXIV\Day12\Garden;
use MMXXIV\Day12\PerimeterCoordinate;

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

    #[SampleAnswer(
        80,
        <<<'GARDEN'
            AAAA
            BBCD
            BBCC
            EEEC
            GARDEN
    )]
    #[SampleAnswer(
        436,
        <<<'GARDEN'
            OOOOO
            OXOXO
            OOOOO
            OXOXO
            OOOOO
            GARDEN
    )]
    #[SampleAnswer(
        236,
        <<<'GARDEN'
            EEEEE
            EXXXX
            EEEEE
            EXXXX
            EEEEE
            GARDEN
    )]
    #[SampleAnswer(
        368,
        <<<'GARDEN'
            AAAAAA
            AAABBA
            AAABBA
            ABBAAA
            ABBAAA
            AAAAAA
            GARDEN
    )]
    #[SampleAnswer(
        1206,
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
    public function part2()
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
            $perimeters = [];

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
                        $perimeters[] = new PerimeterCoordinate(
                            x: $neighbour->x,
                            y: $neighbour->y,
                            direction: $direction,
                        );
                    } elseif ($garden->value($neighbour) === $value) {
                        $areaFrontier->push($neighbour, 0);
                    } else {
                        $perimeters[] =  new PerimeterCoordinate(
                            x: $neighbour->x,
                            y: $neighbour->y,
                            direction: $direction,
                        );

                        $frontier->push($neighbour, 0);
                    }
                }
            }

            /*
             * For every perimeter, check if it was added as being a perimeter on
             * the North/South side, or the East/West side. Depending on that, we
             * add the x or y coordinate respectively to an array of coordinates
             * for that side.
             */

            $sides = [];

            /** @var PerimeterCoordinate $perimeter */
            foreach ($perimeters as $perimeter) {
                if ($perimeter->direction->isVertical()) {
                    $sides['y:' . $perimeter->y . '->' . $perimeter->direction->name][] = $perimeter->x;
                } else {
                    $sides['x:' . $perimeter->x . '->' . $perimeter->direction->name][] = $perimeter->y;
                }
            }

            /*
             * Loop over all sides and as long as the difference between two coordinates
             * is 1, then we're still dealing with the same chunk. Anything greater
             * than 1 means we must have turned a corner.
             */

            $numberOfSides = 0;

            foreach ($sides as $positions) {
                $lastPosition = null;
                sort($positions);

                foreach ($positions as $position) {
                    if ($lastPosition === null || abs($lastPosition - $position) > 1) {
                        $numberOfSides++;
                    }

                    $lastPosition = $position;
                }
            }

            $cost += $area * $numberOfSides;
            $index++;
        }

        return $cost;
    }
};
