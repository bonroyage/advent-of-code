<?php

namespace MMXXIV\Day8;

use App\Utilities\Coordinate;
use Closure;

readonly class Grid
{
    /**
     * @var array<string|int, Coordinate[]>
     */
    private array $antennas;

    public function __construct(
        private array $grid,
    ) {
        $this->antennas = $this->findAntennas();
    }

    private function findAntennas(): array
    {
        /** @var array<string|int, array<Coordinate>> $antennas */
        $antennas = [];

        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === '.') {
                    continue;
                }

                $antennas[$value][] = new Coordinate($x, $y);
            }
        }

        return $antennas;
    }

    public function isOnGrid(Coordinate $coordinate): bool
    {
        return isset($this->grid[$coordinate->y][$coordinate->x]);
    }

    public function antinodes(Closure $callback): int
    {
        $antinodes = [];

        foreach ($this->antennas as $coordinates) {
            foreach ($coordinates as $coordinate1) {
                foreach ($coordinates as $coordinate2) {
                    if ($coordinate1 === $coordinate2) {
                        continue;
                    }

                    $offset = $coordinate1->offset($coordinate2);

                    foreach ($callback($coordinate1, $offset, $this->isOnGrid(...)) as $antinode) {
                        $antinodes[(string) $antinode] = true;
                    }
                }
            }
        }

        return count($antinodes);
    }
}
