<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day5\Line;
use MMXXI\Day5\LineDirection;

return new class('Hydrothermal Venture') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->readFile()
            ->map(fn($line) => new Line($line));
    }

    public function part1(): Part
    {
        $input = $this->input();

        $grid = $this->processGrid($input->filter(fn(Line $line) => $line->direction() !== LineDirection::Diagonal));

        return new Part(
            question: 'Consider only horizontal and vertical lines. At how many points do at least two lines overlap?',
            answer: collect($grid)->flatten()->filter(fn($value) => $value >= 2)->count(),
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $grid = $this->processGrid($input);

        return new Part(
            question: 'Figure out which board will win last. Once it wins, what would its final score be?',
            answer: collect($grid)->flatten()->filter(fn($value) => $value >= 2)->count(),
        );
    }

    private function processGrid(Collection $input): array
    {
        $xMax = $input->max(fn(Line $line) => $line->maxX());
        $yMax = $input->max(fn(Line $line) => $line->maxY());

        $grid = array_fill(0, $yMax + 1, array_fill(0, $xMax + 1, 0));

        /** @var Line $line */
        foreach ($input as $line) {
            if ($line->direction() === LineDirection::Diagonal) {
                $xStep = $line->x1 > $line->x2 ? -1 : 1;
                $yStep = $line->y1 > $line->y2 ? -1 : 1;
                $length = abs($line->x1 - $line->x2);

                $x = $line->x1;
                $y = $line->y1;

                for ($i = 0; $i <= $length; $i++) {
                    $grid[$y][$x]++;

                    $x += $xStep;
                    $y += $yStep;
                }
            } else {
                $y = $line->minY();

                while ($y <= $line->maxY()) {
                    $x = $line->minX();

                    while ($x <= $line->maxX()) {
                        $grid[$y][$x]++;
                        $x++;
                    }

                    $y++;
                }
            }
        }

        return $grid;
    }
};
