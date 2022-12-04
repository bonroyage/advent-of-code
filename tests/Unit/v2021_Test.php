<?php

testTask(2021, 1,
    expectPart1ToBe: 7,
    expectPart2ToBe: 5,
);

testTask(2021, 2,
    expectPart1ToBe: 150,
    expectPart2ToBe: 900,
);

testTask(2021, 3,
    expectPart1ToBe: 198,
    expectPart2ToBe: 230,
);

testTask(2021, 4,
    expectPart1ToBe: 4512,
    expectPart2ToBe: 1924,
);

testTask(2021, 5,
    expectPart1ToBe: 5,
    expectPart2ToBe: 12,
);

testTask(2021, 6,
    expectPart1ToBe: 5934,
    expectPart2ToBe: 26984457539,
);

testTask(2021, 7,
    expectPart1ToBe: 37,
    expectPart2ToBe: 168,
);

testTask(2021, 8,
    expectPart1ToBe: 26,
    expectPart2ToBe: 61229,
);

testTask(2021, 9,
    expectPart1ToBe: 15,
    expectPart2ToBe: 1134,
);

testTask(2021, 10,
    expectPart1ToBe: 26397,
    expectPart2ToBe: 288957,
);

testTask(2021, 11,
    expectPart1ToBe: 1656,
    expectPart2ToBe: 195,
);

testTask(2021, 12,
    expectPart1ToBe: 226,
    expectPart2ToBe: 3509,
);

testTask(2021, 13,
    expectPart1ToBe: 17,
    expectPart2ToBe: <<<ANSWER
#####
#...#
#...#
#...#
#####
.....
.....
ANSWER,
);

testTask(2021, 14,
    expectPart1ToBe: 1588,
    expectPart2ToBe: 2188189693529,
);

testTask(2021, 15,
    expectPart1ToBe: 40,
    expectPart2ToBe: 315,
);

testTask(2021, 16,
    expectPart1ToBe: 31
);

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
