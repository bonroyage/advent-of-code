<?php

namespace MMXXII\Day10;

class CRT
{
    public const SPRITE_SIZE = 3;
    public const NUMBER_OF_ROWS = 6;
    public const PIXELS_PER_ROW = 40;

    private array $registerX = [
        0 => 1,
    ];

    private array $screen;

    public function __construct()
    {
        $this->screen = array_fill(0, self::NUMBER_OF_ROWS, 0);
    }

    public function addx(int $value): void
    {
        $this->registerOp();
        $this->registerOp($value);
    }

    public function noop(): void
    {
        $this->registerOp();
    }

    /**
     * Return the signal strength from the register
     */
    public function signalStrength(int $operation): int
    {
        return $this->registerX[$operation - 1] * $operation;
    }

    /**
     * Render the pixels of the CRT screen
     */
    public function render(?string $on = null, ?string $off = null): string
    {
        return implode(PHP_EOL, array_map(fn($row) => $this->format($row, $on, $off), $this->screen));
    }

    private function registerOp(int $change = 0): void
    {
        $x = last($this->registerX);
        $cycle = count($this->registerX);

        // Calculate the pixel being rendered in the current cycle
        $pixel = $this->pixels(1, $this->column($cycle));

        // Get the sprite for the current cycle
        $sprite = $this->sprite($x);

        // Add the pixel to the CRT row
        $this->screen[$this->row($cycle)] += $pixel & $sprite;

        // Complete the cycle by adding the new value to the X register
        $this->registerX[] = $x + $change;
    }

    private function pixels(int $bits, int $shift = 0): int
    {
        $pixels = (2 ** $bits) - 1;

        return $pixels << self::PIXELS_PER_ROW - $bits - $shift;
    }

    private function sprite(int $shift): int
    {
        $sprite = $this->pixels(self::SPRITE_SIZE);

        // The X register is 1 indexed
        $shift -= 1;

        return $shift >= 0
            ? $sprite >> $shift
            : $sprite << abs($shift);
    }

    private function column(int $cycle): int
    {
        return ($cycle - 1) % self::PIXELS_PER_ROW;
    }

    private function row(int $cycle): int
    {
        return floor(($cycle - 1) / self::PIXELS_PER_ROW);
    }

    private function format(int $sprite, ?string $on = null, ?string $off = null): string
    {
        $sprite &= (2 ** self::PIXELS_PER_ROW) - 1;

        $binary = sprintf('%0' . self::PIXELS_PER_ROW . 'b', $sprite);

        return str_replace([1, 0], [$on ?? '#', $off ?? '.'], $binary);
    }
}
