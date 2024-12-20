<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MMXXII\Day7\Directory;

return new class ('No Space Left On Device') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines();
    }

    #[SampleAnswer(95_437)]
    public function part1(): int
    {
        $tree = $this->getTree();

        $smallDirectories = $tree->directories()
            ->map(fn(Directory $directory) => $directory->size())
            ->filter(fn($size) => $size < 100_000);

        return $smallDirectories->sum();
    }

    #[SampleAnswer(24_933_642)]
    public function part2(): int
    {
        $tree = $this->getTree();

        $directories = $tree->directories()
            ->map(fn(Directory $directory) => $directory->size())
            ->sort();

        $totalSpace = 70_000_000;
        $requiredSpace = 30_000_000;

        $usedSpace = $tree->size();
        $unusedSpace = $totalSpace - $usedSpace;

        $needToFree = $requiredSpace - $unusedSpace;

        return $directories->first(fn(int $directorySize) => $directorySize >= $needToFree);
    }

    private function getTree(): Directory
    {
        $iterator = new ArrayIterator($this->input()->all());

        $root = $pwd = new Directory(name: '/');

        // As long as there are lines left to read, continue
        while ($iterator->valid()) {
            $command = $this->command($iterator);

            // If it's a 'cd' command, store the present working directory
            if ($command[0] === 'cd') {
                if ($command[1] === '..') {
                    $pwd = $pwd->parent;
                } elseif ($command[1] === '/') {
                    $pwd = $root;
                } else {
                    $pwd = $pwd->addDirectory($command[1]);
                }

                $iterator->next();

                continue;
            }

            // Otherwise, if it's an 'ls' command, loop over those lines
            if ($command[0] === 'ls') {
                do {
                    // Read the next line immediately, since we don't care about the ls line itself
                    $iterator->next();

                    if (!$iterator->valid() || $this->isCommand($iterator)) {
                        break;
                    }

                    if (!str_starts_with($iterator->current(), 'dir ')) {
                        [$size, $file] = sscanf($iterator->current(), '%d %s');
                        $pwd->addFile($file, $size);
                    }
                } while (true);
            }
        }

        return $root;
    }

    private function isCommand(ArrayIterator $iterator): bool
    {
        return str_starts_with($iterator->current(), '$ ');
    }

    private function command(ArrayIterator $iterator): ?array
    {
        if (!$this->isCommand($iterator)) {
            throw new RuntimeException('Line is not a command: ' . $iterator->current());
        }

        return explode(' ', Str::after($iterator->current(), '$ '));
    }
};
