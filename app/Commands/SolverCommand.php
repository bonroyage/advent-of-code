<?php

namespace App\Commands;

use App\Exceptions\IncompleteException;
use App\Solver\Day;
use App\Solver\Measure;
use App\Solver\SampleAnswer;
use Closure;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use ReflectionFunction;

use function Termwind\render;

use Throwable;

class SolverCommand extends Command
{
    public const string DAY_PATTERN = '/\/Day(\d+)\/solver\.php$/';

    protected $signature = 'solve {year} {day?} {--sample : Run against sample input instead of real input}';

    protected $description = 'Solve the puzzles';

    public function handle()
    {
        $days = collect(File::glob($this->path() . '/Day*/solver.php'))
            ->mapWithKeys(static function ($file) {
                $day = preg_match(self::DAY_PATTERN, $file, $matches);

                return [$matches[1] => require $file];
            })
            ->sortKeys();

        $index = $this->argument('day');

        if ($index === null) {
            foreach ($days as $index => $day) {
                $this->solveDay($index, $day);
            }

            $this->newLine();

            return Command::SUCCESS;
        }

        if (!$days->has($index)) {
            $this->error('Day not found!');

            return Command::FAILURE;
        }

        $this->solveDay($index, $days->get($index));
    }

    private function solveDay(int $index, Day $day): void
    {
        $this->newLine();
        render('<div class="text-blue font-bold">' . "Day {$index}: {$day->title}" . '</div>');

        $this->solveAnswer($day, 1, $day->part1(...));
        $this->solveAnswer($day, 2, $day->part2(...));
    }

    private function solveAnswer(Day $day, int $part, Closure $callback): void
    {
        try {
            if (!$this->option('sample')) {
                $this->renderReal($part, $callback);

                return;
            }

            $reflection = new ReflectionFunction($callback);
            $attributes = $reflection->getAttributes(SampleAnswer::class);

            if ($attributes === []) {
                render(
                    <<<HTML
                            <div class="ml-2">
                                <span class="mr-2">🟡</span><em>Part {$part}</em> missing
                            </div>
                        HTML,
                );

                return;
            }

            if (count($attributes) === 1) {
                $sample = $attributes[0]->newInstance();
                $sample->part = $part;

                $this->renderSample($day, $part, $callback, $sample);
            } else {
                foreach ($attributes as $i => $attribute) {
                    $sample = $attribute->newInstance();
                    $sample->part = $part;

                    $this->renderSample($day, $part, $callback, $sample, $i);
                }
            }
        } catch (IncompleteException) {
            render(
                <<<HTML
                        <div class="ml-2">
                            <span class="mr-2">🟡</span><em>Part {$part}</em> incomplete
                        </div>
                    HTML,
            );
        }
    }

    private function renderSample(Day $day, int $part, Closure $callback, SampleAnswer $sample, ?int $index = null): void
    {
        $day->sample = $sample;

        $expectedAnswer = $sample->answer;

        try {
            // Load the file once to ensure reading the file for the first time
            // is not part of the time measurement.
            $day->getFile();
        } catch (Throwable) {
        }

        [$answer, $elapsed] = Measure::block($callback);
        $isCorrect = $expectedAnswer === $answer;

        $elapsed = Measure::format($elapsed);

        if (str_contains($answer, "\n")) {
            $answer = "\n" . $answer;
        }

        if (str_contains($expectedAnswer, "\n")) {
            $expectedAnswer = "\n\n" . $expectedAnswer;
        } else {
            $expectedAnswer = '(' . $expectedAnswer . ')';
        }

        $formattedAnswer = nl2br($answer);
        $formattedExpectedAnswer = nl2br($expectedAnswer);

        $icon = $isCorrect ? '🟢' : '🔴';

        $answers = $isCorrect
            ? "<span class=\"text-green-400\">{$formattedAnswer}</span>"
            : "<span class=\"text-red-400\">{$formattedAnswer}</span> <span class=\"text-yellow-400\">{$formattedExpectedAnswer}</span>";

        $sample = $index === null ? '' : ", sample {$index}";

        render(
            <<<HTML
                    <div class="ml-2">
                        <span class="mr-2">{$icon}</span><em>Part {$part}{$sample} in <span class="text-cyan-400">{$elapsed}</span>:&nbsp;</em>
                        {$answers}
                    </div>
                HTML,
        );
    }

    private function renderReal(int $part, Closure $callback): void
    {
        [$answer, $elapsed] = Measure::block($callback);

        $elapsed = Measure::format($elapsed);

        if (str_contains($answer, "\n")) {
            $answer = "\n" . $answer;
        }

        $formattedAnswer = nl2br($answer);

        $this->newLine();

        render(
            <<<HTML
                    <div class="ml-2">
                        <em>Part {$part} in <span class="text-cyan-400">{$elapsed}</span>:&nbsp;</em>
                        <span class="text-yellow-400">{$formattedAnswer}</span>
                    </div>
                HTML,
        );
    }

    private function path(): string
    {
        $realBase = rtrim($this->absolute(base_path('editions')), '/') . '/';
        $realUserPath = $this->absolute($realBase . '/' . $this->argument('year'));

        if (!str_starts_with($realUserPath, $realBase)) {
            $this->error('No hacking allowed here');

            exit;
        }

        return $realUserPath;
    }

    private function absolute(string $path): string
    {
        $result = realpath($path);

        if ($result) {
            return $result;
        }

        $this->error('Oops! Can\'t find this edition');

        exit;
    }
}
