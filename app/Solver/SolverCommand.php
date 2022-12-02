<?php

namespace App\Solver;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use function Termwind\render;

class SolverCommand extends Command
{

    protected $signature = 'solve {year} {day?}';


    public function handle()
    {
        $days = collect(File::glob($this->path() . '/*.php'))
            ->mapWithKeys(function ($file) {
                $basename = basename($file, '.php');
                return [$basename => include $file];
            });

        if ($this->argument('day') !== null) {
            $index = $this->argument('day');
        } else {
            foreach ($days as $day => $dayObject) {
                $this->line(sprintf("  Day <comment>%s</comment>: %s", $day, $dayObject->title));
            }

            $index = $this->ask('What day do you want to calculate?', 'all');
        }

        if ($index === 'all') {
            foreach ($days as $index => $day) {
                $this->solveDay($index, $day);
            }

            return Command::SUCCESS;
        }

        if (!$days->has($index)) {
            $this->error('Day not found!');
            return Command::FAILURE;
        }

        $this->solveDay($index, $days->get($index));
    }


    private function solveDay(int $index, Day $day)
    {
        $this->title("Day {$index}: {$day->title}");

        /** @var \App\Solver\Part $part */
        foreach ($day->handle() as $part) {
            render('<div><em class="ml-1">' . $part->question . '</em></div>');
            render('<div><div class="px-1 text-yellow-400">' . $part->answer . '</div></div>');
            $this->newLine();
        }
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
