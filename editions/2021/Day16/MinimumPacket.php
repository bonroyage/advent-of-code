<?php

namespace MMXXI\Day16;

class MinimumPacket extends OperatorPacket
{

    public function value(): int
    {
        return min($this->values());
    }

}
