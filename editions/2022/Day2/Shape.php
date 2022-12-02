<?php

namespace MMXXII\Day2;

enum Shape: int
{

    case Rock = 1;
    case Paper = 2;
    case Scissors = 3;


    public function result(Shape $otherShape): Result
    {
        if ($this === $otherShape) {
            return Result::Draw;
        }

        return match ($this) {
            Shape::Rock => $otherShape === Shape::Scissors,
            Shape::Paper => $otherShape === Shape::Rock,
            Shape::Scissors => $otherShape === Shape::Paper,
        } ? Result::Win : Result::Lose;
    }

}
