<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class ('Scratchcards') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()->map(function (string $line) {
            preg_match('/Card\s+(\d+):((\s+(\d+))+)\s\|((\s+(\d+))+)/', $line, $matches);

            $winning = str($matches[2])->squish()->explode(' ');
            $got = str($matches[5])->squish()->explode(' ');

            return [
                'card' => $matches[1],
                'winning' => $winning,
                'got' => $got,
            ];
        });
    }

    #[SampleAnswer(13)]
    public function part1(): int
    {
        return $this->input()->map(function ($card) {
            $count = $card['winning']->intersect($card['got'])->count();

            if ($count > 1) {
                return pow(2, $count - 1);
            }

            return $count;
        })->sum();
    }

    #[SampleAnswer(30)]
    public function part2(): int
    {
        $cards = $this->input()->mapWithKeys(fn($card) => [$card['card'] => 1]);

        foreach ($this->input() as $card) {
            $count = $card['winning']->intersect($card['got'])->count();

            for ($i = $card['card'] + 1; $i <= $card['card'] + $count; $i++) {
                $cards[$i] = $cards[$i] + $cards->get($card['card']);
            }
        }

        return $cards->sum();
    }
};
