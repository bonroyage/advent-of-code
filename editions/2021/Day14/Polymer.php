<?php

namespace MMXXI\Day14;

class Polymer
{

    private array $cache;


    public function __construct(private readonly string $template, private readonly array $rules)
    {
        $this->cache = [];
    }


    public function run(int $numberOfSteps): array
    {
        $counts = array_fill_keys(array_unique(array_values($this->rules)), 0);

        for ($i = 0; $i < (strlen($this->template) - 1); $i++) {
            $pair = substr($this->template, $i, 2);
            $counts[$pair[0]]++;

            foreach ($this->calculateForPair($pair, $numberOfSteps) as $element => $count) {
                $counts[$element] += $count;
            }
        }

        $counts[$this->template[-1]]++;

        asort($counts);

        return $counts;
    }


    private function calculateForPair(string $pair, int $numberOfSteps): array
    {
        if ($numberOfSteps === 0) {
            return [];
        }

        // If we've previously computed the counts for this specific pair at the
        // current number of steps, return that to prevent double work
        if (isset($this->cache[$numberOfSteps][$pair])) {
            return $this->cache[$numberOfSteps][$pair];
        }

        $insertion = $this->rules[$pair];

        $ex = [
            $insertion => 1,
        ];

        // Run with the left side of the pair and the inserted character
        foreach ($this->calculateForPair($pair[0] . $insertion, $numberOfSteps - 1) as $element => $count) {
            $ex[$element] = ($ex[$element] ?? 0) + $count;
        }

        // Run with the inserted character and the right side of the pair
        foreach ($this->calculateForPair($insertion . $pair[1], $numberOfSteps - 1) as $element => $count) {
            $ex[$element] = ($ex[$element] ?? 0) + $count;
        }

        $this->cache[$numberOfSteps][$pair] = $ex;

        return $ex;
    }

}
