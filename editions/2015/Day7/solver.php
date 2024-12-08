<?php

use App\Solver\Day;
use App\Solver\Part;
use Illuminate\Support\Collection;

return new class ('Some Assembly Required') extends Day {
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/(.*) -> (\w+)/', $line, $matches);

                $instruction = $matches[1];
                $provides = $matches[2];

                if (preg_match('/^(\w+)$/', $instruction, $matches)) {
                    return [
                        'provides' => $provides,
                        'depends' => array_filter([
                            is_numeric($matches[1]) ? null : $matches[1],
                        ]),
                        'instruction' => 'value',
                        'value' => $matches[1],
                    ];
                }

                if (preg_match('/^(\w+) (AND|OR|LSHIFT|RSHIFT) (\w+)$/', $instruction, $matches)) {
                    return [
                        'provides' => $provides,
                        'depends' => array_filter([
                            is_numeric($matches[1]) ? null : $matches[1],
                            is_numeric($matches[3]) ? null : $matches[3],
                        ]),
                        'instruction' => strtolower($matches[2]),
                        'lhs' => $matches[1],
                        'rhs' => $matches[3],
                    ];
                }

                if (preg_match('/^NOT (\w+)$/', $instruction, $matches)) {
                    return [
                        'provides' => $provides,
                        'depends' => array_filter([
                            is_numeric($matches[1]) ? null : $matches[1],
                        ]),
                        'instruction' => 'not',
                        'variable' => $matches[1],
                    ];
                }
            })
            ->keyBy('provides');
    }

    public function part1(): Part
    {
        $known = [];

        $input = $this->input();

        while ($input->isNotEmpty()) {
            foreach ($input as $key => $instruction) {
                if (array_diff($instruction['depends'], array_keys($known)) === []) {
                    $known[$key] = $this->compute($instruction, $known);
                    $input->forget($key);
                }
            }
        }

        return new Part(
            answer: $known['a'] ?? null,
        );
    }

    public function part2(): Part
    {
        $known = [
            'b' => $this->part1()->answer,
        ];

        $input = $this->input();
        $input->forget('b');

        while ($input->isNotEmpty()) {
            foreach ($input as $key => $instruction) {
                if (array_diff($instruction['depends'], array_keys($known)) === []) {
                    $known[$key] = $this->compute($instruction, $known);
                    $input->forget($key);
                }
            }
        }

        return new Part(
            answer: $known['a'] ?? null,
        );
    }

    private function compute(array $instruction, array $known): ?int
    {
        if ($instruction['instruction'] === 'value') {
            return $this->valueOrKnown($instruction['value'], $known);
        }

        if ($instruction['instruction'] === 'lshift') {
            $lhs = $this->valueOrKnown($instruction['lhs'], $known);
            $rhs = $this->valueOrKnown($instruction['rhs'], $known);

            return $lhs << $rhs;
        }

        if ($instruction['instruction'] === 'rshift') {
            $lhs = $this->valueOrKnown($instruction['lhs'], $known);
            $rhs = $this->valueOrKnown($instruction['rhs'], $known);

            return $lhs >> $rhs;
        }

        if ($instruction['instruction'] === 'and') {
            $lhs = $this->valueOrKnown($instruction['lhs'], $known);
            $rhs = $this->valueOrKnown($instruction['rhs'], $known);

            return $lhs & $rhs;
        }

        if ($instruction['instruction'] === 'or') {
            $lhs = $this->valueOrKnown($instruction['lhs'], $known);
            $rhs = $this->valueOrKnown($instruction['rhs'], $known);

            return $lhs | $rhs;
        }

        if ($instruction['instruction'] === 'not') {
            $variable = $this->valueOrKnown($instruction['variable'], $known);

            return ~$variable & 0b1111_1111_1111_1111;
        }

        return null;
    }

    private function valueOrKnown(string $value, array $known): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        return $known[$value];
    }
};
