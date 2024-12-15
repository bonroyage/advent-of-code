<?php

namespace MMXXIV\Day15;

use App\Utilities\Coordinate;
use App\Utilities\Direction;
use Generator;
use RuntimeException;
use UnexpectedValueException;

class Grid
{
    public function __construct(
        private array $grid,
    ) {}

    public function findStart(): Coordinate
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === '@') {
                    return new Coordinate(
                        x: $x,
                        y: $y,
                    );
                }
            }
        }

        throw new RuntimeException('Start not found');
    }

    public function isBlocked(Coordinate $coordinate): bool
    {
        return $this->grid[$coordinate->y][$coordinate->x] === '#';
    }

    public function isBox(Coordinate $coordinate): bool
    {
        return $this->grid[$coordinate->y][$coordinate->x] === 'O'
            || $this->grid[$coordinate->y][$coordinate->x] === '['
            || $this->grid[$coordinate->y][$coordinate->x] === ']';
    }

    public function moveSimpleBox(Coordinate $oldLocation, Coordinate $newLocation): void
    {
        $this->grid[$oldLocation->y][$oldLocation->x] = '.';
        $this->grid[$newLocation->y][$newLocation->x] = 'O';
    }

    public function moveSelf(Coordinate $oldLocation, Coordinate $newLocation): void
    {
        $this->grid[$oldLocation->y][$oldLocation->x] = '.';
        $this->grid[$newLocation->y][$newLocation->x] = '@';
    }

    public function move(Coordinate $coordinate, Direction $direction): Coordinate
    {
        $moveTo = $coordinate->moveInDirection($direction);

        /*
         * If the new coordinate is a wall, then we can't move at all.
         */
        if ($this->isBlocked($moveTo)) {
            return $coordinate;
        }

        /*
         * If the new coordinate is an empty spot, then we simply move ourselves.
         */
        if (!$this->isBox($moveTo)) {
            $this->moveSelf(
                oldLocation: $coordinate,
                newLocation: $moveTo,
            );

            return $moveTo;
        }

        $line = match ($direction) {
            Direction::Up => array_reverse(array_slice(array_column($this->grid, $coordinate->x), 0, $coordinate->y)),
            Direction::Down => array_slice(array_column($this->grid, $coordinate->x), $coordinate->y + 1),
            Direction::Left => array_reverse(array_slice($this->grid[$coordinate->y], 0, $coordinate->x)),
            Direction::Right => array_slice($this->grid[$coordinate->y], $coordinate->x + 1),
            default => throw new UnexpectedValueException('Unexpected direction')
        };

        foreach ($line as $index => $value) {
            /*
             * We've hit a wall without finding an empty spot. We can't move
             * at all.
             */
            if ($value === '#') {
                return $coordinate;
            }

            if ($value === '.') {
                $this->moveSimpleBox(
                    oldLocation: $moveTo,
                    newLocation: $coordinate->moveInDirection($direction, $index + 1),
                );

                $this->moveSelf(
                    oldLocation: $coordinate,
                    newLocation: $moveTo,
                );

                return $moveTo;
            }
        }

        throw new RuntimeException('How did we get here?');
    }

    private function tryHorizontalMove(Coordinate $self, Direction $direction)
    {
        $line = $this->grid[$self->y];

        if ($direction === Direction::Left) {
            $line = array_splice($line, 0, $self->x);
            $line = array_reverse($line);
        } else {
            $line = array_splice($line, $self->x + 1);
        }

        $pos = array_search('.', $line, true);
        $wall = array_search('#', $line, true);

        if ($pos === false) {
            // No empty spot
            return false;
        }

        if ($wall < $pos) {
            // Hit wall before empty spot
            return false;
        }

        array_splice($line, $pos, 1);

        if ($direction === Direction::Left) {
            $line = array_reverse($line);
            $line[] = '.';

            array_splice($this->grid[$self->y], 0, $self->x, $line);
        } else {
            array_unshift($line, '.');

            array_splice($this->grid[$self->y], $self->x + 1, replacement: $line);
        }

        return true;
    }

    public function move2(Coordinate $coordinate, Direction $direction): Coordinate
    {
        $moveTo = $coordinate->moveInDirection($direction);

        /*
         * If the new coordinate is a wall, then we can't move at all.
         */
        if ($this->isBlocked($moveTo)) {
            return $coordinate;
        }

        /*
         * If the new coordinate is an empty spot, then we simply move ourselves.
         */
        if (!$this->isBox($moveTo)) {
            $this->moveSelf(
                oldLocation: $coordinate,
                newLocation: $moveTo,
            );

            return $moveTo;
        }

        if ($direction->isHorizontal()) {
            $canMoveToEmptySpot = $this->tryHorizontalMove($coordinate, $direction);

            if (!$canMoveToEmptySpot) {
                return $coordinate;
            }

            $this->moveSelf(
                oldLocation: $coordinate,
                newLocation: $moveTo,
            );

            return $moveTo;
        }

        $boxesInVerticalLine = $this->canMoveVertically(
            coordinate: $coordinate->moveInDirection($direction),
            direction: $direction,
        );

        if ($boxesInVerticalLine === null) {
            return $coordinate;
        }

        foreach ($boxesInVerticalLine as $serializedCoordinate => $value) {
            $unserializedCoordinate = unserialize($serializedCoordinate);
            $this->grid[$unserializedCoordinate->y][$unserializedCoordinate->x] = '.';
        }

        foreach ($boxesInVerticalLine as $serializedCoordinate => $value) {
            $unserializedCoordinate = unserialize($serializedCoordinate);
            $unserializedCoordinate = $unserializedCoordinate->moveInDirection($direction);
            $this->grid[$unserializedCoordinate->y][$unserializedCoordinate->x] = $value;
        }

        $this->moveSelf(
            oldLocation: $coordinate,
            newLocation: $moveTo,
        );

        return $moveTo;
    }

    private function canMoveVertically(Coordinate $coordinate, Direction $direction): ?array
    {
        $value = $this->grid[$coordinate->y][$coordinate->x];

        if ($value === '#') {
            return null;
        }

        if ($value === '.') {
            return [];
        }

        $toSelf = $this->canMoveVertically(
            coordinate: $coordinate->moveInDirection($direction),
            direction: $direction,
        );

        if ($toSelf === null) {
            return null;
        }

        $partner = $coordinate->moveInDirection(
            $value === '['
                ? Direction::Right
                : Direction::Left,
        );

        $toPartner = $this->canMoveVertically(
            coordinate: $partner->moveInDirection($direction),
            direction: $direction,
        );

        if ($toPartner === null) {
            return null;
        }

        return [
            serialize($coordinate) => $value,
            serialize($partner) => $this->grid[$partner->y][$partner->x],
            ...$toSelf,
            ...$toPartner,
        ];
    }

    public function boxes(): Generator
    {
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === 'O' || $value === '[') {
                    yield new Coordinate(
                        x: $x,
                        y: $y,
                    );
                }
            }
        }
    }

    public function expand(): void
    {
        foreach ($this->grid as $y => $row) {
            $this->grid[$y] = collect($row)->flatMap(fn($value) => match ($value) {
                '.' => ['.', '.'],
                '#' => ['#', '#'],
                '@' => ['@', '.'],
                'O' => ['[', ']'],
            })->all();
        }
    }
}
