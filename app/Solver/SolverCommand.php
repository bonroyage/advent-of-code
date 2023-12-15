<?php

namespace App\Solver;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

class SolverCommand extends Command
{
    protected $signature = 'solve {year} {day?} {--sample}';

    public function handle()
    {
        $days = collect(File::glob($this->path().'/*.php'))
            ->mapWithKeys(function ($file) {
                $basename = basename($file, '.php');

                return [$basename => include $file];
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

        if ($this->option('sample')) {
            $day->sample = 1;
        }

        $part1 = $day->part1();

        $this->newLine();
        render('<div class="ml-2"><em>Part 1:</em> <span class="text-yellow-400">'.nl2br($part1->answer).'</span></div>');

        if ($this->option('sample')) {
            $day->sample = 2;
        }

        $part2 = $day->part2();

        $this->newLine();
        render('<div class="ml-2"><em>Part 2:</em> <span class="text-yellow-400">'.nl2br($part2->answer).'</span></div>');
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
