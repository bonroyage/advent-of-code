<?php

use App\Solver\Day;
use App\Solver\Part;
use MMXXI\Day16\Packet;
use MMXXI\Day16\Transmission;

return new class('Packet Decoder') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): string
    {
        return $this->readFile()[0];
    }

    public function part1(): Part
    {
        $input = $this->input();

        $transmission = new Transmission($input);

        $packet = Packet::read($transmission);

        return new Part(
            question: 'Decode the structure of your hexadecimal-encoded BITS transmission; what do you get if you add up the version numbers in all packets?',
            answer: $this->sumVersions($packet),
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $transmission = new Transmission($input);

        $packet = Packet::read($transmission);

        return new Part(
            question: 'What do you get if you evaluate the expression represented by your hexadecimal-encoded BITS transmission?',
            answer: $packet->value(),
        );
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
