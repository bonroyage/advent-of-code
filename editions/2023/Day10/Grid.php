<?php

namespace MMXXIII\Day10;

class Grid
{
    private array $grid;
    private array $start;

    public function __construct(array $grid)
    {
        $this->grid = $grid;

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $pipe) {
                if ($pipe === 'S') {
                    $this->start = [$x, $y];
                    break 2;
                }
            }
        }
    }

    public function getGrid(): string
    {
        $str = '';

        foreach ($this->grid as $row) {
            $str .= implode('', $row).PHP_EOL;
        }

        return $str;
    }

    public function getStart(): array
    {
        return $this->start;
    }

    public function changePipe(array $node, string $pipe): void
    {
        [$x, $y] = $node;

        $this->grid[$y][$x] = $pipe;
    }

    public function neighbouringNodes(array $node): array
    {
        [$x, $y] = $node;

        $pipe = $this->grid[$y][$x];

        $north = [$x, $y - 1];
        $south = [$x, $y + 1];
        $west = [$x - 1, $y];
        $east = [$x + 1, $y];

        return match ($pipe) {
            '.', ':' => [],
            'F' => [$south, $east],
            '-' => [$west, $east],
            '7' => [$west, $south],
            '|' => [$north, $south],
            'J' => [$west, $north],
            'L' => [$north, $east],
            default => null,
        };
    }

    public function determineStartPipe(): string
    {
        $neighboursContainingStart = [];
        [$x, $y] = $this->start;

        foreach (['E' => [1, 0], 'N' => [0, -1], 'W' => [-1, 0], 'S' => [0, 1]] as $direction => [$dx, $dy]) {
            if (!isset($this->grid[$y + $dy][$x + $dx])) {
                continue;
            }

            if (in_array($this->start, $this->neighbouringNodes([$x + $dx, $y + $dy]), true)) {
                $neighboursContainingStart[] = $direction;
            }
        }

        $pipe = match ($neighboursContainingStart) {
            ['E', 'S'] => 'F',
            ['E', 'W'] => '-',
            ['W', 'S'] => '7',
            ['N', 'S'] => '|',
            ['N', 'W'] => 'J',
            ['E', 'N'] => 'L',
        };

        $this->changePipe($this->start, $pipe);

        return $pipe;
    }

    public function removeNonLoopNodes(array $loop): void
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $pipe) {
                if (!in_array($x, $loop[$y] ?? [], true)) {
                    $this->grid[$y][$x] = '.';
                }
            }
        }
    }

    public function determineInOutsideLoop(): int
    {
        $inside = 0;

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $pipe) {
                /*
                 * We're dealing with an actual part of the pipe, so we know for
                 * sure that this is not going to be inside or outside the loop.
                 */
                if ($pipe !== '.') {
                    continue;
                }

                $counts = array_count_values(array_slice($row, 0, $x));

                /*
                 * Count all the pieces of pipe that connect to the top. Two |
                 * cancel each other out, as do L--J, but a combination of L--7
                 * or F--J do not.
                 */
                $count = $counts['|'] ?? 0;
                $count += $counts['L'] ?? 0;
                $count += $counts['J'] ?? 0;

                /*
                 * If it's odd, then we are inside the loop.
                 */
                if ($count % 2 === 1) {
                    $inside++;
                    $this->grid[$y][$x] = '*';
                }
            }
        }

        return $inside;
    }
}
