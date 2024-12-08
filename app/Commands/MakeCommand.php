<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCommand extends Command
{
    protected $signature = 'make {year} {day}';

    protected $description = 'Copy the stubs for a new day';

    public function handle(): void
    {
        $year = $this->argument('year');
        $day = $this->argument('day');

        $target = base_path('editions/' . $year . '/Day' . $day);

        if (File::exists($target)) {
            $this->error('Day already exists!');

            return;
        }

        File::copyDirectory(base_path('stubs/day'), $target);
    }
}
