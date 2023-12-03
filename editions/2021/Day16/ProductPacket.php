<?php

namespace MMXXI\Day16;

class ProductPacket extends OperatorPacket
{
    public function value(): int
    {
        return array_product($this->values());
    }
}
