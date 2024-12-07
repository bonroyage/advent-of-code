<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use MMXXI\Day16\Packet;
use MMXXI\Day16\Transmission;

return new class('Packet Decoder') extends Day
{
    private function input(): string
    {
        return $this->getFile();
    }

    #[SampleAnswer(31)]
    public function part1(): int
    {
        $input = $this->input();

        $transmission = new Transmission($input);

        $packet = Packet::read($transmission);

        return $this->sumVersions($packet);
    }

    public function part2(): int
    {
        $input = $this->input();

        $transmission = new Transmission($input);

        $packet = Packet::read($transmission);

        return $packet->value();
    }

    private function sumVersions(Packet $packet)
    {
        $version = $packet->version;

        foreach ($packet->subpackets() as $subpacket) {
            $version += $this->sumVersions($subpacket);
        }

        return $version;
    }
};
