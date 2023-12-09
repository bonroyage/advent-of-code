<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXII\Day14\Grid;

return new class('Regolith Reservoir') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        $paths = $this->input();

        $grid = new Grid();

        foreach ($paths as $path) {
            preg_match_all('/(\d+),(\d+)/', $path, $matches, PREG_SET_ORDER);
            $grid->addPathOfRocks(array_map(fn(array $match) => [$match[1], $match[2]], $matches));
        }

        for ($i = 0; ; $i++) {
            $lastNode = [0, 0];

            do {
                foreach ([0, -1, 1] as $dx) {
                    $nextNode = [$lastNode[0] + $dx, $lastNode[1] + 1];

                    /*
                     * If the next node doesn't exist because it's off the grid
                     * then the sand will fall into the abyss and we have our
                     * answer.
                     */
                    if ($grid->isAbyss($nextNode)) {
                        return new Part(
                            answer: $i,
                        );
                    }

                    if (!$grid->isBlocked($nextNode)) {
                        $lastNode = $nextNode;

                        continue 2;
                    }
                }

                $grid->dropSand($lastNode);
                break;
            } while (true);
        }
    }

    public function part2(): Part
    {
        $paths = $this->input();

        $grid = new Grid();

        foreach ($paths as $path) {
            preg_match_all('/(\d+),(\d+)/', $path, $matches, PREG_SET_ORDER);
            $grid->addPathOfRocks(array_map(fn(array $match) => [$match[1], $match[2]], $matches));
        }

        $grid->setFloor();

        for ($i = 1; ; $i++) {
            $lastNode = [0, 0];

            do {
                foreach ([0, -1, 1] as $dx) {
                    $nextNode = [$lastNode[0] + $dx, $lastNode[1] + 1];

                    if (!$grid->isBlocked($nextNode)) {
                        $lastNode = $nextNode;

                        continue 2;
                    }
                }

                $grid->dropSand($lastNode);
                break;
            } while (true);

            /*
             * If sand was dropped at the source then we can't drop anymore.
             */
            if ($lastNode === [0, 0]) {
                return new Part(
                    answer: $i,
                );
            }
        }
    }
};
