<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use PHPUnit\Framework\Assert;

foreach (glob(__DIR__.'/../../editions/*/Day*/solver.php') as $file) {
    preg_match('/\/editions\/(\d+)\/Day(\d+)\/solver\.php$/', $file, $matches);

    $year = $matches[1];
    $day = $matches[2];

    /** @var Day $class */
    $class = require $file;

    $reflection = new ReflectionFunction($class->part1(...));
    $attributes = $reflection->getAttributes(SampleAnswer::class);

    if ($attributes !== []) {
        $expectedAnswer = $attributes[0]->newInstance()->answer;

        test("{$year}, day {$day}, part 1", function () use ($class, $expectedAnswer) {
            $class->sample = 1;
            Assert::assertSame($expectedAnswer, $class->part1());
        });
    }

    $reflection = new ReflectionFunction($class->part2(...));
    $attributes = $reflection->getAttributes(SampleAnswer::class);

    if ($attributes !== []) {
        $expectedAnswer = $attributes[0]->newInstance()->answer;

        test("{$year}, day {$day}, part 2", function () use ($class, $expectedAnswer) {
            $class->sample = 2;
            Assert::assertSame($expectedAnswer, $class->part2());
        });
    }
}
