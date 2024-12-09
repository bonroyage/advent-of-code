<?php

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\SampleAnswer;
use PHPUnit\Framework\Assert;

function createTest(Day $day, SampleAnswer $attribute, int $part, Closure $fn): Closure
{
    return function () use ($day, $attribute, $part, $fn) {
        $attribute->part = $part;
        $day->sample = $attribute;

        try {
            Assert::assertSame($attribute->answer, $fn());
        } catch (IncompleteException) {
            Assert::markTestIncomplete('Not implemented yet');
        }
    };
}

collect(glob(__DIR__ . '/../../editions/*/Day*/solver.php'))
    ->sort(SORT_NATURAL)
    ->each(function (string $file) {
        preg_match('/\/editions\/(\d+)\/Day(\d+)\/solver\.php$/', $file, $matches);

        [, $year, $day] = $matches;

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
    });
