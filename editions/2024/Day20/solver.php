<?php

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\SampleAnswer;
use App\Utilities\AStar\AStar;
use App\Utilities\AStar\PathNode;
use App\Utilities\Direction;
use Illuminate\Support\Collection;
use MMXXIV\Day20\Map;

return new class ('Race Condition') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(fn(string $line) => str_split($line));
    }

    #[SampleAnswer(8)]
    public function part1(): int
    {
        $threshold = $this->sample ? 12 : 100;

        $sum = 0;

        $grid = $this->input()->all();

        $map = new Map($grid);

        $aStar = new AStar($map);

        $start = $map->findStart();
        $end = $map->findEnd();

        $shortestPath = $aStar->shortestPath(from: $start, to: $end);
        $totalTime = $shortestPath->gScore;

        $timeToReach = $this->timeToReach($shortestPath);

        while ($shortestPath = $shortestPath->parent) {
            $timeToCoordinate = $timeToReach[(string) $shortestPath->coordinate];

            foreach (Direction::Orthogonal as $direction) {
                // Same direction as where we came from
                if ($direction->opposite() === $shortestPath->coordinate->direction) {
                    continue;
                }

                $firstCoordinate = $shortestPath->coordinate->moveInDirection($direction);

                // Node is already clear, so that's the route we would have taken
                if (!$map->isWall($firstCoordinate)) {
                    continue;
                }

                $secondCoordinate = $shortestPath->coordinate->moveInDirection($direction, 2);

                if (!$map->isOnGrid($secondCoordinate) || $map->isWall($secondCoordinate)) {
                    continue;
                }

                $time = $timeToCoordinate + ($totalTime - $timeToReach[(string) $secondCoordinate]) + 2;
                $saving = $totalTime - $time;

                if ($saving >= $threshold) {
                    $sum++;
                }
            }
        }

        return $sum;
    }

    private function timeToReach(PathNode $node): array
    {
        $costs = [(string) $node->coordinate => $node->gScore];

        while ($node = $node->parent) {
            $costs[(string) $node->coordinate] = $node->gScore;
        }

        return $costs;
    }

    #[SampleAnswer(null)]
    public function part2()
    {
        throw new IncompleteException();
    }
};
