<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class('Binary Diagnostic') extends Day
{
    public function handle(): Generator
    {
        yield $this->part1();
        yield $this->part2();
    }

    private function input(): Collection
    {
        return $this->getFileLines();
    }

    public function part1(): Part
    {
        $input = $this->input();

        $gamma = 0;
        $epsilon = 0;

        for ($i = 0; $i < strlen($input->first()); $i++) {
            [$gammaBit, $epsilonBit] = $this->checkBits($input, $i);
            $gamma = ($gamma << 1) + $gammaBit;
            $epsilon = ($epsilon << 1) + $epsilonBit;
        }

        return new Part(
            answer: $gamma * $epsilon,
        );
    }

    public function part2(): Part
    {
        $input = $this->input();

        $oxygen = '';
        $co2 = '';

        for ($i = 0; $i < strlen($input->first()); $i++) {
            $oxygenOptions = $input->filter(fn($item) => str_starts_with($item, $oxygen));
            $co2Options = $input->filter(fn($item) => str_starts_with($item, $co2));

            if ($oxygenOptions->containsOneItem()) {
                $oxygen = $oxygenOptions->first();
            } else {
                $oxygen .= $this->checkBits($oxygenOptions, $i)[0];
            }

            if ($co2Options->containsOneItem()) {
                $co2 = $co2Options->first();
            } else {
                $co2 .= $this->checkBits($co2Options, $i)[1];
            }
        }

        return new Part(
            answer: bindec($oxygen) * bindec($co2),
        );
    }

    private function checkBits(Collection $input, int $index): array
    {
        $input = $input->map(fn($bits) => str_split($bits))->pluck($index)->all();

        $bits = array_count_values($input);

        $on = $bits[1] ?? 0;
        $off = $bits[0] ?? 0;

        if ($on >= $off) {
            return [1, 0];
        } else {
            return [0, 1];
        }
    }
};
