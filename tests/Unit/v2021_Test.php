<?php

test('2021, day 16, part 2', function ($input, $output) {
    $transmission = new \MMXXI\Day16\Transmission($input);
    $packet = \MMXXI\Day16\Packet::read($transmission);

    expect($packet->value())->toBe($output);
})->with([
    ['C200B40A82', 3],
    ['04005AC33890', 54],
    ['880086C3E88112', 7],
    ['CE00C43D881120', 9],
    ['D8005AC2A8F0', 1],
    ['F600BC2D8F', 0],
    ['9C005AC2F8F0', 0],
    ['9C0141080250320F1802104A08', 1],
]);
