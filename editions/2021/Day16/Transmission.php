<?php

namespace MMXXI\Day16;

class Transmission
{
    public readonly string $originalBinary;
    private string $binary;
    private int $position = 0;

    public function __construct(string $hex)
    {
        $this->originalBinary = $this->binary = preg_replace_callback('/./', function ($value) {
            return str_pad(base_convert($value[0], 16, 2), 4, '0', STR_PAD_LEFT);
        }, $hex);
    }

    public function readBinary(int $bits): string
    {
        $read = substr($this->binary, 0, $bits);

        $this->position += $bits;
        $this->binary = substr($this->binary, $bits);

        return $read;
    }

    public function readDecimal(int $bits): int
    {
        return bindec($this->readBinary($bits));
    }

    public function readBoolean(): bool
    {
        return $this->readDecimal(1) === 1;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
