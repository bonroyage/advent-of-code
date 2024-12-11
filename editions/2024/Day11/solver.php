<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use MMXXIV\Day11\StoneChanger;

return new class ('Plutonian Pebbles') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines(' ')->map(fn($stone) => (int) $stone);
    }

    #[SampleAnswer(55312)]
    public function part1(): int
    {
        $input = $this->input()->all();
        $changer = new StoneChanger();

        return $changer->countStones($input, 25);
    }

    public function part2(): int
    {
        $input = $this->input()->all();
        $changer = new StoneChanger();

        return $changer->countStones($input, 75);
    }
};
