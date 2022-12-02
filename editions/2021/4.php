<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;
use MMXXI\Day4\Board;

return new class ('Giant Squid') extends Day {

    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }


    private function drawn(): Collection
    {
        $drawn = $this->readFile(__DIR__ . '/4.txt')->first();

        return str($drawn)
            ->explode(',');
    }


    private function boards(): Collection
    {
        return $this->readFile(__DIR__ . '/4.txt', PHP_EOL . PHP_EOL)
            ->skip(1)
            ->values()
            ->map(fn(string $board) => new Board(
                str($board)
                    ->explode(PHP_EOL)
                    ->map(fn(string $line) => str($line)
                        ->trim()
                        ->split('/\s+/')
                    )
                    ->toArray()
            ));
    }


    private function part1(): Part
    {
        return new Part(
            question: 'To guarantee victory against the giant squid, figure out which board will win first. What will your final score be if you choose that board?',
            answer: $this->findWinningBoard()
        );
    }


    private function part2(): Part
    {
        return new Part(
            question: 'Figure out which board will win last. Once it wins, what would its final score be?',
            answer: $this->findLastWinningBoard()
        );
    }


    private function findWinningBoard(): ?int
    {
        $boards = $this->boards();

        foreach ($this->drawn() as $number) {
            /** @var Board $board */
            foreach ($boards as $board) {
                $board->draw($number);

                if ($board->hasBingo()) {
                    return $board->value() * $number;
                }
            }
        }

        return null;
    }


    private function findLastWinningBoard(): ?int
    {
        $boards = $this->boards();

        foreach ($this->drawn() as $number) {
            // Remove any boards that already have bingo
            $boards = $boards->reject(fn(Board $board) => $board->hasBingo());

            foreach ($boards as $board) {
                $board->draw($number);
            }

            if ($boards->reject(fn(Board $board) => $board->hasBingo())->isEmpty()) {
                return $boards->first()->value() * $number;
            }
        }

        return null;
    }

};
