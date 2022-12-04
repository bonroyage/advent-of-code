<?php

namespace MMXXI\Day16;

class MaximumPacket extends OperatorPacket
{

    public function value(): int
    {
        return max($this->values());
    }

}
