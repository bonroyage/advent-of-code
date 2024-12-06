<?php

namespace App\Utilities;

enum Direction
{
    case North;
    case East;
    case South;
    case West;

    case NorthWest;
    case NorthEast;
    case SouthWest;
    case SouthEast;
    public const Up = self::North;
    public const Down = self::South;
    public const Left = self::West;
    public const Right = self::East;
    public const Orthogonal = [self::North, self::East, self::South, self::West];
    public const Diagonal = [self::NorthWest, self::NorthEast, self::SouthEast, self::SouthWest];

    public function offset(): array
    {
        return match ($this) {
            self::North => [0, -1],
            self::East => [1, 0],
            self::South => [0, 1],
            self::West => [-1, 0],
            self::NorthWest => [-1, -1],
            self::NorthEast => [1, -1],
            self::SouthWest => [-1, 1],
            self::SouthEast => [1, 1],
        };
    }

    public function opposite(): self
    {
        return match ($this) {
            self::North => self::South,
            self::East => self::West,
            self::South => self::North,
            self::West => self::East,
            self::NorthWest => self::SouthEast,
            self::NorthEast => self::SouthWest,
            self::SouthWest => self::NorthEast,
            self::SouthEast => self::NorthWest,
        };
    }

    public function right90(): self
    {
        return match ($this) {
            self::North => self::East,
            self::East => self::South,
            self::South => self::West,
            self::West => self::North,
            self::NorthWest => self::NorthEast,
            self::NorthEast => self::SouthEast,
            self::SouthEast => self::SouthWest,
            self::SouthWest => self::NorthWest,
        };
    }

    public function left90(): self
    {
        return $this->right90()->opposite();
    }

    public function right45(): self
    {
        return match ($this) {
            self::North => self::NorthEast,
            self::NorthEast => self::East,
            self::East => self::SouthEast,
            self::SouthEast => self::South,
            self::South => self::SouthWest,
            self::SouthWest => self::West,
            self::West => self::NorthWest,
            self::NorthWest => self::North,
        };
    }

    public function left45(): self
    {
        return $this->left90()->right45();
    }
}
