<?php

use App\Solver\Day;
use App\Solver\Part;
use MMXXIII\Day10\Grid;

return new class('') extends Day
{
    private function input(): Grid
    {
        return new Grid(
            $this->getFileLines()
                ->map(fn(string $line) => str_split($line))->toArray(),
        );
    }

    public function part1(): Part
    {
        $grid = $this->input();
        $grid->determineStartPipe();
        $nodes = [$grid->getStart()];
        $previousNodes = [];

        for ($distance = 1; ; $distance++) {
            $nextNodes = [];

            foreach ($nodes as $node) {
                $neighbours = $grid->neighbouringNodes($node);
                foreach ($neighbours as $neighbour) {
                    if (!in_array($neighbour, $previousNodes, true)) {
                        $nextNodes[] = $neighbour;
                    }
                }
            }

            $previousNodes = $nodes;
            $nodes = $nextNodes;

            /*
             * We've reached the same piece of pipe coming from both directions
             */
            if ($nodes[0] === $nodes[1]) {
                break;
            }
        }

        return new Part(
            answer: $distance,
        );
    }

    public function part2(): Part
    {
        $grid = $this->input();
        $grid->determineStartPipe();
        $nodes = [$grid->getStart()];
        $previousNodes = [];
        $loop = [];

        for ($distance = 1; ; $distance++) {
            $nextNodes = [];

            foreach ($nodes as $node) {
                $neighbours = $grid->neighbouringNodes($node);
                foreach ($neighbours as $neighbour) {
                    if (!in_array($neighbour, $previousNodes, true)) {
                        $loop[$neighbour[1]][] = $neighbour[0];
                        $nextNodes[] = $neighbour;
                    }
                }
            }

            $previousNodes = $nodes;
            $nodes = $nextNodes;

            /*
             * We've reached the same piece of pipe coming from both directions
             */
            if ($nodes[0] === $nodes[1]) {
                break;
            }
        }

        $grid->removeNonLoopNodes($loop);

        $inside = $grid->determineInOutsideLoop();

        return new Part(
            answer: $inside,
        );
    }
};
