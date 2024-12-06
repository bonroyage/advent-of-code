<?php

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(null)]
    public function part1()
    {
        throw new IncompleteException();
    }

    #[SampleAnswer(null)]
    public function part2()
    {
        throw new IncompleteException();
    }
};
