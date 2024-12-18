<?php

namespace App\Utilities\AStar;

use App\Utilities\Coordinate;
use Closure;
use Ds\PriorityQueue;

readonly class AStar
{
    public function __construct(
        public Pathfinding $map,
    ) {}

    public function shortestPath(Coordinate $from, Coordinate $to): ?PathNode
    {
        $shortestPath = null;

        $this->process(
            from: $from,
            to: $to,
            onGoalReached: function (PathNode $currentNode) use (&$shortestPath) {
                if (($shortestPath?->fScore ?? PHP_INT_MAX) > $currentNode->fScore) {
                    $shortestPath = $currentNode;
                }
            },
            continueOnEqualCost: true,
        );

        return $shortestPath;
    }

    public function shortestPaths(Coordinate $from, Coordinate $to): ?array
    {
        $shortestPaths = [];

        $this->process(
            from: $from,
            to: $to,
            onGoalReached: function (PathNode $currentNode) use (&$shortestPaths) {
                if (($shortestPaths[0]?->fScore ?? PHP_INT_MAX) > $currentNode->fScore) {
                    $shortestPaths = [$currentNode];
                } elseif (($shortestPaths[0]?->fScore ?? PHP_INT_MAX) === $currentNode->fScore) {
                    $shortestPaths[] = $currentNode;
                }
            },
            continueOnEqualCost: false,
        );

        return $shortestPaths === [] ? null : $shortestPaths;
    }

    private function process(Coordinate $from, Coordinate $to, Closure $onGoalReached, bool $continueOnEqualCost): void
    {
        $frontier = new PriorityQueue();
        $frontier->push(new PathNode($from), 0);

        $explored = [];
        $explored[serialize($from)] = 0;

        while (!$frontier->isEmpty()) {
            /** @var PathNode $currentNode */
            $currentNode = $frontier->pop();
            $currentCoordinate = $currentNode->coordinate;

            if ($this->map->goalReached($currentCoordinate, $to)) {
                $onGoalReached($currentNode);

                continue;
            }

            foreach ($this->map->neighbours($currentCoordinate) as $neighbour) {
                $moveCost = $this->map->costToMove(from: $currentCoordinate, to: $neighbour);
                $newCost = $currentNode->gScore + $moveCost;
                $serializedNeighbour = serialize($neighbour);

                if (($explored[$serializedNeighbour] ?? PHP_INT_MAX) < $newCost || ($continueOnEqualCost && ($explored[$serializedNeighbour] ?? PHP_INT_MAX) === $newCost)) {
                    continue;
                }

                $explored[$serializedNeighbour] = $newCost;

                $nextNode = new PathNode(
                    coordinate: $neighbour,
                    parent: $currentNode,
                    moveCost: $moveCost,
                    hScore: $this->map->distance($currentCoordinate, $neighbour),
                );

                $distance = abs($neighbour->x - $to->x) + abs($neighbour->y - $to->y);

                $frontier->push($nextNode, $distance);
            }
        }
    }
}
