<?php

namespace MMXXI\Day4;

class Board
{

    private array $board;


    public function __construct(private readonly array $initialBoard)
    {
        $this->board = $this->initialBoard;
    }


    public function draw(string $value)
    {
        for ($row = 0; $row < 5; $row++) {
            for ($column = 0; $column < 5; $column++) {
                if ($this->board[$row][$column] === $value) {
                    $this->board[$row][$column] = null;
                }
            }
        }
    }


    public function value(): int
    {
        return collect($this->board)->flatten()->sum();
    }


    public function hasBingo(): bool
    {
        for ($i = 0; $i < 5; $i++) {
            if (count(array_filter($this->board[$i], is_null(...))) === 5) {
                return true;
            }

            if (count(array_filter(array_column($this->board, $i), is_null(...))) === 5) {
                return true;
            }
        }

        return false;
    }

}
