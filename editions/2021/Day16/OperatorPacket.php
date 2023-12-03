<?php

namespace MMXXI\Day16;

abstract class OperatorPacket extends Packet
{
    /**
     * @var \MMXXI\Day16\Packet[]
     */
    private array $subpackets = [];

    public function addSubpacket(Packet $packet): void
    {
        $this->subpackets[] = $packet;
    }

    public function subpackets(): array
    {
        return $this->subpackets;
    }

    /**
     * @return int[]
     */
    public function values(): array
    {
        return array_map(fn(Packet $packet) => $packet->value(), $this->subpackets);
    }

    public static function read(Transmission $transmission): static
    {
        $packet = new static();

        $typeLengthId = $transmission->readDecimal(1);

        if ($typeLengthId === 0) {
            // the next 15 bits are a number that represents the total length in bits of the sub-packets contained by this packet
            $lengthInBits = $transmission->readDecimal(15);

            while ($lengthInBits > 0) {
                $subpacket = Packet::read($transmission);
                $lengthInBits -= $subpacket->size;
                $packet->addSubpacket($subpacket);
            }
        } else {
            // the next 11 bits are a number that represents the number of sub-packets immediately contained by this packet
            $numberOfSubpackets = $transmission->readDecimal(11);

            for ($i = 0; $i < $numberOfSubpackets; $i++) {
                $packet->addSubpacket(Packet::read($transmission));
            }
        }

        return $packet;
    }
}
