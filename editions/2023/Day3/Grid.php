<?php

namespace MMXXIII\Day3;

class Grid
{
    private array $parts = [];
    private array $symbols = [];

    public function __construct(public readonly array $grid)
    {
        foreach ($grid as $y => $line) {
            $startIndex = null;
            $endIndex = null;
            $number = '';

            foreach ($line as $x => $char) {
                if (is_numeric($char)) {
                    $startIndex ??= $x;
                    $endIndex = $x;
                    $number .= $char;
                    continue;
                }

                if ($char !== '.') {
                    $this->saveSymbol($char, $x, $y);
                }

                if ($number !== '') {
                    $this->savePartNumber($number, $y, $startIndex, $endIndex);
                }

                $startIndex = null;
                $number = '';
            }

            if ($number !== '') {
                $this->savePartNumber($number, $y, $startIndex, $endIndex);
            }
        }
    }

    private function saveSymbol(string $char, int $x, int $y): void
    {
        $this->symbols[] = [
            'char' => $char,
            'node' => [$x, $y],
        ];
    }

    private function savePartNumber(int $partNumber, int $y, int $x0, int $x1): void
    {
        $nodes = [];

        for ($i = $x0; $i <= $x1; $i++) {
            $nodes[] = [$i, $y];
        }

        $this->parts[] = [
            'number' => $partNumber,
            'nodes' => $nodes,
        ];
    }

    public function run(): array
    {
        $symbols = [];

        foreach ($this->symbols as $symbol) {
            $neighbours = $this->nextNodes($symbol['node']);
            $symbolParts = [];

            foreach ($this->parts as $part) {
                foreach ($part['nodes'] as $node) {
                    if (in_array($node, $neighbours, true)) {
                        $symbolParts[] = $part['number'];
                        continue 2;
                    }
                }
            }

            if ($symbolParts !== []) {
                $symbols[] = [
                    'char' => $symbol['char'],
                    'numbers' => $symbolParts,
                ];
            }
        }

        return $symbols;
    }

    private function nextNodes(array $node): array
    {
        $neighbours = [];

        foreach (range(-1, 1) as $dy) {
            foreach (range(-1, 1) as $dx) {
                if ($dy === 0 && $dx === 0) {
                    continue;
                }

                $neighbours[] = [$node[0] + $dx, $node[1] + $dy];
            }
        }

        return $neighbours;
    }
}
