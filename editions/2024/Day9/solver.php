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
        $disk = collect($this->input())
            ->flatMap(fn(int $size, int $index) => array_fill(
                start_index: 0,
                count: $size,
                value: $index % 2 === 0 ? $index / 2 : null,
            ));

        $freePositions = $disk->whereNull()->keys();

        return $disk
            ->reverse()
            ->whereNotNull()
            ->reduce(function (int $checksum, int $fileId, int $blockPosition) use ($freePositions) {
                return $checksum + $fileId * min($blockPosition, $freePositions->shift() ?? INF);
            }, 0);
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

        foreach (array_reverse($blocks) as $block) {
            if ($block->fileId === null) {
                continue;
            }

            foreach ($blocks as $freeBlock) {
                if ($freeBlock->fileId !== null || $freeBlock->size < $block->size || $freeBlock->offset >= $block->offset) {
                    continue;
                }

                $block->offset = $freeBlock->offset;

                $freeBlock->size -= $block->size;
                $freeBlock->offset += $block->size;

                break;
            }

            $checksum += $block->fileId * ($block->size / 2) * ($block->offset * 2 + $block->size - 1);
        }

        return $checksum;
    }
};
