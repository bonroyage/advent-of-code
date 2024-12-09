<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;

return new class ('Disk Fragmenter') extends Day {
    private function input(): array
    {
        return str_split($this->getFile());
    }

    #[SampleAnswer(1928)]
    public function part1(): int
    {
        $offset = 0;
        $disk = [];
        $freePositions = [];

        foreach ($this->input() as $index => $size) {
            $size = (int) $size;

            if ($size === 0) {
                continue;
            }

            if ($index % 2 === 0) {
                $disk += array_fill($offset, $size, $index / 2);
            } else {
                array_push($freePositions, ...range($offset, $offset + $size - 1));
            }

            $offset += $size;
        }

        $checksum = 0;

        foreach (array_reverse($disk, true) as $blockPosition => $fileId) {
            $checksum += $fileId * min($blockPosition, array_shift($freePositions) ?? $blockPosition);
        }

        return $checksum;
    }

    #[SampleAnswer(2858)]
    public function part2(): int
    {
        $input = $this->input();

        /** @var object{size: int, offset: int, fileId: int|null}[] $blocks */
        $blocks = [];
        $offset = 0;

        foreach ($input as $index => $size) {
            $size = (int) $size;
            $blocks[] = (object) [
                'size' => $size,
                'offset' => $offset,
                'fileId' => $index % 2 === 1 ? null : $index / 2,
            ];
            $offset += $size;
        }

        $checksum = 0;

        // Take any processed block off the away because we can never place blocks
        // after themselves. This makes the inner foreach loop more efficient.
        while ($block = array_pop($blocks)) {
            if ($block->fileId === null) {
                continue;
            }

            foreach ($blocks as $index => $freeBlock) {
                if ($freeBlock->fileId !== null || $freeBlock->size < $block->size) {
                    continue;
                }

                $block->offset = $freeBlock->offset;

                $freeBlock->size -= $block->size;
                $freeBlock->offset += $block->size;

                // Remove any free block that has been completely filled. This also
                // makes the loop more efficient on subsequent calls.
                if ($freeBlock->size <= 0) {
                    unset($blocks[$index]);
                }

                break;
            }

            $checksum += $block->fileId * ($block->size / 2) * ($block->offset * 2 + $block->size - 1);
        }

        return $checksum;
    }
};
