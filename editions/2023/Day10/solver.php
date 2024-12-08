<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use MMXXIII\Day10\Grid;

return new class ('') extends Day {
    private function input(): Grid
    {
        return new Grid(
            $this->getFileLines()
                ->map(fn(string $line) => str_split($line))->toArray(),
        );
    }

    #[SampleAnswer(8)]
    public function part1(): int
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

        return $distance;
    }

    #[SampleAnswer(10)]
    public function part2(): int
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

        return $grid->determineInOutsideLoop();
    }
};
