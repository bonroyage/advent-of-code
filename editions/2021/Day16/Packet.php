<?php

namespace MMXXI\Day16;

abstract class Packet implements \JsonSerializable
{
    public int $version;

    public int $size;

    public static function read(Transmission $transmission): static
    {
        $start = $transmission->getPosition();
        $version = $transmission->readDecimal(3);
        $typeID = $transmission->readDecimal(3);

        $result = match ($typeID) {
            0 => SumPacket::read($transmission),
            1 => ProductPacket::read($transmission),
            2 => MinimumPacket::read($transmission),
            3 => MaximumPacket::read($transmission),
            4 => LiteralPacket::read($transmission),
            5 => GreaterThanPacket::read($transmission),
            6 => LessThanPacket::read($transmission),
            7 => EqualToPacket::read($transmission),
        };

        $result->version = $version;
        $result->size = $transmission->getPosition() - $start;

        return $result;
    }

    public function subpackets(): array
    {
        return [];
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
            'version' => $this->version,
            'value' => $this->value(),
        ];
    }

    abstract public function value(): int;
}
