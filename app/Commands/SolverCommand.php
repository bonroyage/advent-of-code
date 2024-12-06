<?php

namespace App\Commands;

use App\Solver\Day;
use App\Solver\Measure;
use App\Solver\SampleAnswer;
use Closure;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use ReflectionFunction;

use function Termwind\render;

class SolverCommand extends Command
{
    const DAY_PATTERN = '/\/Day(\d+)\/solver\.php$/';

    protected $signature = 'solve {year} {day?} {--sample}';

    public function handle()
    {
        $days = collect(File::glob($this->path().'/Day*/solver.php'))
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
        render('<div class="text-blue font-bold">'."Day {$index}: {$day->title}".'</div>');

        $this->solveAnswer($day, 1, $day->part1(...));
        $this->solveAnswer($day, 2, $day->part2(...));
    }

    private function solveAnswer(Day $day, int $part, Closure $callback): void
    {
        if ($this->option('sample')) {
            $day->sample = $part;

            $reflection = new ReflectionFunction($callback);
            $attributes = $reflection->getAttributes(SampleAnswer::class);

            $expectedAnswer = $attributes === [] ? null : $attributes[0]->newInstance()->answer;
        }

        [$answer, $elapsed] = Measure::block($callback);

        $elapsed = Measure::format($elapsed);
        $formattedAnswer = nl2br($answer);

        $this->newLine();

        if (isset($expectedAnswer)) {
            $isCorrect = $expectedAnswer === $answer;
            $icon = $isCorrect ? '✅' : '❌';

            $answers = $isCorrect
                ? "<span class=\"text-yellow-400\">{$formattedAnswer}</span>"
                : <<<HTML
                <span class="text-red-400">{$formattedAnswer}</span> <span class="text-green-400">({$expectedAnswer})</span>
                HTML;

            render(
                <<<HTML
                <div class="ml-2">
                    <span class="mr-2">{$icon}</span>
                    <em>Part {$part} in <span class="text-cyan-400">{$elapsed}</span>:&nbsp;</em>
                    {$answers}
                </div>
HTML,
            );
        } else {
            render(
                <<<HTML
                <div class="ml-2">
                    <em>Part {$part} in <span class="text-cyan-400">{$elapsed}</span>:&nbsp;</em>
                    <span class="text-yellow-400">{$formattedAnswer}</span>
                </div>
HTML,
            );
        }
    }

    private function path(): string
    {
        $realBase = rtrim($this->absolute(base_path('editions')), '/').'/';
        $realUserPath = $this->absolute($realBase.'/'.$this->argument('year'));

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
