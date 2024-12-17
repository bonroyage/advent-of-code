<?php

namespace MMXXIV\Day17;

use UnexpectedValueException;

class Registers
{
    public function __construct(
        public int $a = 0,
        public int $b = 0,
        public int $c = 0,
        public array $output = [],
        public int $pointer = 0,
    ) {}

    public function comboOperand(int $literal): int
    {
        return match ($literal) {
            4 => $this->a,
            5 => $this->b,
            6 => $this->c,
            7 => throw new UnexpectedValueException('Reserved'),
            default => $literal,
        };
    }

    public function process(array $instructions): string
    {
        while (isset($instructions[$this->pointer])) {
            $opcode = $instructions[$this->pointer];
            $operand = $instructions[$this->pointer + 1];

            $newPointer = $this->processOpcode($opcode, $operand);

            if ($newPointer === null) {
                $this->pointer += 2;
            } else {
                $this->pointer = $newPointer;
            }
        }

        return implode(',', $this->output);
    }

    private function processOpcode(int $opcode, int $literalOperand): ?int
    {
        // jnz
        if ($opcode === 3) {
            return $this->a === 0 ? null : $literalOperand;
        }

        $comboOperand = match ($literalOperand) {
            4 => $this->a,
            5 => $this->b,
            6 => $this->c,
            7 => throw new UnexpectedValueException('Reserved'),
            default => $literalOperand,
        };

        match ($opcode) {
            0 /* adv */ => $this->a = (int) ($this->a / pow(2, $comboOperand)),
            1 /* bxl */ => $this->b ^= $literalOperand,
            2 /* bst */ => $this->b = $comboOperand % 8,
            4 /* bxc */ => $this->b ^= $this->c,
            5 /* out */ => $this->output[] = $comboOperand % 8,
            6 /* bdv */ => $this->b = (int) ($this->a / pow(2, $comboOperand)),
            7 /* cdv */ => $this->c = (int) ($this->a / pow(2, $comboOperand)),
        };

        return null;
    }
}
