<?php

namespace MMXXI\Day16;

class LiteralPacket extends Packet
{
    public function __construct(
        public readonly int $value,
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public static function read(Transmission $transmission): static
    {
        $literalValue = '';

        do {
            $continue = $transmission->readBoolean();
            $literalValue .= $transmission->readBinary(4);
        } while ($continue);

        return new self(bindec($literalValue));
    }
}
