<?php

namespace MMXXIV\Day6;

use App\Utilities\Coordinate;

class Movement
{
    private const MOVES = [
        'n' => [-1, 0, 'e', 's'],
        'e' => [0, 1, 's', 'w'],
        's' => [1, 0, 'w', 'n'],
        'w' => [0, -1, 'n', 'e'],
    ];

    public function __construct(
        public readonly ?Coordinate $from,
        public readonly ?Coordinate $to,
        public readonly string $inDirectionOf,
    ) {
    }

    public function __toString(): string
    {
        return "{$this->to}->{$this->inDirectionOf}";
    }

    public function continueStraight(): Movement
    {
        return new Movement(
            from: $this->to,
            to: $this->to->move(
                x: self::MOVES[$this->inDirectionOf][1],
                y: self::MOVES[$this->inDirectionOf][0],
            ),
            inDirectionOf: $this->inDirectionOf,
        );
    }

    public function turnRight(): Movement
    {
        $newDirection = self::MOVES[$this->inDirectionOf][2];

        return new self(
            from: $this->to,
            to: $this->to->move(
                x: self::MOVES[$newDirection][1],
                y: self::MOVES[$newDirection][0],
            ),
            inDirectionOf: $newDirection,
        );
    }

    public function uTurn(): Movement
    {
        return new self(
            from: $this->from,
            to: $this->from,
            inDirectionOf: self::MOVES[$this->inDirectionOf][3],
        );
    }

    public function offGrid(): Movement
    {
        return new Movement(
            from: $this->to,
            to: null,
            inDirectionOf: $this->inDirectionOf,
        );
    }
}
