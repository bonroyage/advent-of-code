<?php

use App\Solver\Day;
use App\Solver\Part;

return new class('The Ideal Stocking Stuffer') extends Day
{
    private function input(): string
    {
        return $this->getFile();
    }

    public function part1(): Part
    {
        $input = $this->input();
        $prefix = '';
        $i = 0;

        while ($prefix !== '00000') {
            $prefix = substr(md5($input.++$i), 0, 5);
        }

        return new Part(
            answer: $i,
        );
    }

    public function part2(): Part
    {
        $input = $this->input();
        $prefix = '';
        $i = 0;

        while ($prefix !== '000000') {
            $prefix = substr(md5($input.++$i), 0, 6);
        }

        return new Part(
            answer: $i,
        );
    }
};
