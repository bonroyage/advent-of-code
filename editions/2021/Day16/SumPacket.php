<?php

namespace MMXXI\Day16;

class SumPacket extends OperatorPacket
{
    public function value(): int
    {
        return array_sum($this->values());
    }
}
