<?php

namespace MMXXI\Day10;

class Line
{
    public readonly array $unclosed;

    public function __construct(public readonly string $line)
    {
        $chunks = [];

        foreach ($this->characters() as $char) {
            if ($this->isOpeningTag($char)) {
                $chunks[] = $char;
            } else {
                $last = last($chunks);

                $expect = $this->closingTagFor($last);

                if ($expect !== $char) {
                    throw new UnexpectedCharacterException($expect, $char);
                }

                array_pop($chunks);
            }
        }

        $this->unclosed = $chunks;
    }

    public function characters(): array
    {
        return str_split($this->line);
    }

    public function isOpeningTag(string $char): bool
    {
        return in_array($char, ['(', '[', '{', '<'], true);
    }

    public function closingTagFor(string $char): string
    {
        return match ($char) {
            '(' => ')',
            '[' => ']',
            '{' => '}',
            '<' => '>',
        };
    }
}
