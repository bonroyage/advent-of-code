<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use MMXXI\Day16\Packet;
use MMXXI\Day16\Transmission;

return new class ('Packet Decoder') extends Day {
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

    #[SampleAnswer(3, 'C200B40A82')]
    #[SampleAnswer(54, '04005AC33890')]
    #[SampleAnswer(7, '880086C3E88112')]
    #[SampleAnswer(9, 'CE00C43D881120')]
    #[SampleAnswer(1, 'D8005AC2A8F0')]
    #[SampleAnswer(0, 'F600BC2D8F')]
    #[SampleAnswer(0, '9C005AC2F8F0')]
    #[SampleAnswer(1, '9C0141080250320F1802104A08')]
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
