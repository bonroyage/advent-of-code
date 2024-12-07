<?php

use App\Solver\Day;
use App\Solver\Part;

return new class('Not Quite Lisp') extends Day
{
    private function input(): string
    {
        return $this->getFile();
    }

    public function part1(): Part
    {
        $input = $this->input();
        $increase = substr_count($input, '(');
        $decrease = substr_count($input, ')');

        return new Part(
            answer: $increase - $decrease,
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $floor = 0;

        foreach (str_split($input) as $position => $char) {
            if ($char === '(') {
                $floor++;
            } else {
                $floor--;
            }

            if ($floor < 0) {
                return new Part(
                    answer: $position + 1,
                );
            }
        }

        return new Part(
            answer: null,
        );
    }
};
