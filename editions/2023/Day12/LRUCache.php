<?php

namespace MMXXIII\Day12;

use SplDoublyLinkedList;

class LRUCache
{
    private array $cache = [];
    private SplDoublyLinkedList $list;

    public function __construct(
        private ?int $capacity = null,
    ) {
        $this->list = new SplDoublyLinkedList();
    }

    public function has($key): bool
    {
        return isset($this->cache[$key]);
    }

    public function get($key)
    {
        if (!isset($this->cache[$key])) {
            return null;
        }

        $this->list->rewind();

        while ($this->list->current() !== $key) {
            $this->list->next();
        }

        $this->list->rewind();

        $this->list->unshift($this->list->pop());

        return $this->cache[$key];
    }

    public function put($key, $value): void
    {
        if ($this->capacity !== null && count($this->cache) >= $this->capacity) {
            $removedKey = $this->list->pop();
            unset($this->cache[$removedKey]);
        }

        $this->list->unshift($key);
        $this->cache[$key] = $value;
    }
}
