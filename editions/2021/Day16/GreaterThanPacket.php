<?php

namespace MMXXI\Day16;

class GreaterThanPacket extends OperatorPacket
{
    public function value(): int
    {
        $values = $this->values();

        if (count($values) !== 2) {
            throw new \RuntimeException('GreaterThanPacket expects exactly two subpackets, got '.count($values));
        }

        return $values[0] > $values[1] ? 1 : 0;
    }
}
