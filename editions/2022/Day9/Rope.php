<?php

namespace MMXXII\Day9;

class Rope
{

    private array $knots;


    public function __construct(int $knots = 0)
    {
        $this->knots = ['H' => new Knot([0, 0])];

        for ($i = 1; $i <= $knots; $i++) {
            $this->knots[$i] = last($this->knots)->makeChaser();
        }
    }


    public function move(string $direction, int $amount): void
    {
        $movement = match ($direction) {
            'U' => [0, -1],
            'D' => [0, 1],
            'L' => [-1, 0],
            'R' => [1, 0]
        };

        for ($i = 1; $i <= $amount; $i++) {
            $this->knots['H']->move($movement);
        }
    }


    public function tail(): Knot
    {
        return last($this->knots);
    }

}
