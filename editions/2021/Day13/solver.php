<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day13\Grid;

return new class ('Transparent Origami') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines(PHP_EOL . PHP_EOL);
    }

    private function dots(): Collection
    {
        return str($this->input()[0])
            ->explode(PHP_EOL)
            ->map(fn($point) => explode(',', $point));
    }

    private function instructions(): Collection
    {
        return str($this->input()[1])
            ->explode(PHP_EOL)
            ->map(fn($instruction) => sscanf($instruction, 'fold along %1s=%d'));
    }

    #[SampleAnswer(17)]
    public function part1(): int
    {
        $grid = new Grid($this->dots()->all());

        foreach ($this->instructions() as [$axis, $position]) {
            if ($axis === 'y') {
                $grid->foldUp($position);

                break;
            } elseif ($axis === 'x') {
                $grid->foldLeft($position);

                break;
            }
        }

        return $grid->visible();
    }

    #[SampleAnswer(
        <<<'ANSWER'
            #####
            #...#
            #...#
            #...#
            #####
            .....
            .....
            ANSWER
    )]
    public function part2(): string
    {
        $grid = new Grid($this->dots()->all());

        foreach ($this->instructions() as [$axis, $position]) {
            if ($axis === 'y') {
                $grid->foldUp($position);
            } elseif ($axis === 'x') {
                $grid->foldLeft($position);
            }
        }

        return trim($grid->prettyPrint());
    }
};
