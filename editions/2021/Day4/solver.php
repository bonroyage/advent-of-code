<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXI\Day4\Board;

return new class ('Giant Squid') extends Day {
    private function drawn(): Collection
    {
        $drawn = $this->getFileLines()->first();

        return str($drawn)
            ->explode(',');
    }

    private function boards(): Collection
    {
        return $this->getFileLines(PHP_EOL . PHP_EOL)
            ->skip(1)
            ->values()
            ->map(fn(string $board) => new Board(
                str($board)
                    ->explode(PHP_EOL)
                    ->map(
                        fn(string $line) => str($line)
                            ->trim()
                            ->split('/\s+/'),
                    )
                    ->toArray(),
            ));
    }

    #[SampleAnswer(4_512)]
    public function part1(): int
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

        throw new Exception('Expected an answer');
    }

    #[SampleAnswer(1_924)]
    public function part2(): int
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

        throw new Exception('Expected an answer');
    }
};
