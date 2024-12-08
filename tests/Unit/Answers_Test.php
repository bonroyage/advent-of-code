<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use PHPUnit\Framework\Assert;

function createTest(Day $day, SampleAnswer $attribute, int $part, Closure $fn): Closure
{
    return function () use ($day, $attribute, $part, $fn) {
        $attribute->part = $part;
        $day->sample = $attribute;

        Assert::assertSame($attribute->answer, $fn());
    };
}

foreach (glob(__DIR__ . '/../../editions/*/Day*/solver.php') as $file) {
    preg_match('/\/editions\/(\d+)\/Day(\d+)\/solver\.php$/', $file, $matches);

    $year = $matches[1];
    $day = $matches[2];

    /** @var Day $class */
    $class = require $file;

    $parts = [
        1 => $class->part1(...),
        2 => $class->part2(...),
    ];

    foreach ($parts as $part => $fn) {
        $reflection = new ReflectionFunction($fn);
        $attributes = $reflection->getAttributes(SampleAnswer::class);

        if ($attributes === []) {
            test("{$year}, day {$day}, part {$part}", function () {
                Assert::markTestIncomplete('#[SampleAnswer] missing');
            });
        } elseif (count($attributes) === 1) {
            test(
                "{$year}, day {$day}, part {$part}",
                createTest($class, $attributes[0]->newInstance(), $part, $fn),
            );
        } else {
            foreach ($attributes as $i => $attribute) {
                test(
                    "{$year}, day {$day}, part {$part}, sample {$i}",
                    createTest($class, $attribute->newInstance(), $part, $fn),
                );
            }
        }
    }
}
