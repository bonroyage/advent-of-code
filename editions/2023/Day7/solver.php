<?php

use App\Solver\Day;
use App\Solver\SampleAnswer;
use Illuminate\Support\Collection;

return new class('Camel Cards') extends Day
{
    private function input(): Collection
    {
        return $this->getFileLines()
            ->map(function (string $line) {
                preg_match('/(\w{5})\s+(\d+)/', $line, $matches);

                return [
                    'cards' => $matches[1],
                    'bid' => (int) $matches[2],
                ];
            });
    }

    #[SampleAnswer(6_440)]
    public function part1(): int
    {
        return $this->winnings(
            $this->input()
                ->map(fn(array $hand) => [
                    ...$hand,
                    'sort' => $this->sortValuesPart1($hand['cards']),
                ]),
        );
    }

    #[SampleAnswer(5_905)]
    public function part2(): int
    {
        return $this->winnings(
            $this->input()
                ->map(fn(array $hand) => [
                    ...$hand,
                    'sort' => $this->sortValuesPart2($hand['cards']),
                ]),
        );
    }

    private function winnings(Collection $handsWithSort): int
    {
        return $handsWithSort
            ->sort(function ($a, $b) {
                foreach ($a['sort'] as $index => $aValue) {
                    $compare = $aValue <=> $b['sort'][$index];

                    if ($compare !== 0) {
                        return $compare;
                    }
                }

                return 0;
            })
            ->values()
            ->map(fn(array $card, int $index) => $card['bid'] * ($index + 1))
            ->sum();
    }

    private function sortValuesPart1(string $cards): array
    {
        $cards = str_split($cards);
        $order = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];

        $values = array_map(fn(string $card) => array_search($card, $order, true), $cards);
        $occurrencesPerValue = array_count_values($values);

        $valuesList = array_values($occurrencesPerValue);
        rsort($valuesList);

        return [
            $this->typeFromValues($valuesList),
            ...$values,
        ];
    }

    private function sortValuesPart2(string $cards): array
    {
        $jokers = substr_count($cards, 'J');
        $cards = str_split($cards);
        $order = ['J', '2', '3', '4', '5', '6', '7', '8', '9', 'T', 'Q', 'K', 'A'];

        $values = array_map(fn(string $card) => array_search($card, $order, true), $cards);
        $occurrencesPerValue = array_count_values($values);

        if ($jokers > 0) {
            unset($occurrencesPerValue[1]);
        }

        $valuesList = array_values($occurrencesPerValue);
        rsort($valuesList);

        if ($jokers === 5) {
            $valuesList[0] = 5;
        } elseif ($jokers > 0) {
            $valuesList[0] += $jokers;
        }

        return [
            $this->typeFromValues($valuesList),
            ...$values,
        ];
    }

    private function typeFromValues(array $values): int
    {
        return match (true) {
            /* five-of-a-kind  */ $values[0] === 5 => 6,
            /* four-of-a-kind  */ $values[0] === 4 => 5,
            /* full house      */ $values[0] === 3 && $values[1] === 2 => 4,
            /* three-of-a-kind */ $values[0] === 3 => 3,
            /* two pair        */ $values[0] === 2 && $values[1] === 2 => 2,
            /* one pair        */ $values[0] === 2 => 1,
            /* high card       */ default => 0,
        };
    }
};
