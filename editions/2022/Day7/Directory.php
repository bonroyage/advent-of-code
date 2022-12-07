<?php

namespace MMXXII\Day7;

use Illuminate\Support\Collection;

class Directory implements Node
{

    private array $children = [];


    public function __construct(
        public readonly string     $name,
        public readonly ?Directory $parent = null,
    )
    {
    }


    public function addDirectory(string $name): Directory
    {
        $node = new Directory(
            name: $name,
            parent: $this
        );

        $this->children[$name] = $node;

        return $node;
    }


    public function addFile(string $name, int $size): File
    {
        $node = new File(
            name: $name,
            size: $size,
            parent: $this
        );

        $this->children[$name] = $node;

        return $node;
    }


    public function size(): int
    {
        $sizes = array_map(fn(Node $node) => $node->size(), $this->children);

        return array_sum($sizes);
    }


    public function pwd(): string
    {
        $pwd = [];

        $node = $this;

        while ($node) {
            $pwd[] = $node->name;
            $node = $node->parent;
        }

        return preg_replace('/(\/+)/', '/', implode('/', array_reverse($pwd)));
    }


    public function directories(): Collection
    {
        return collect($this->children)
            ->whereInstanceOf(Directory::class)
            ->map(fn(Directory $node) => $node->directories())
            ->prepend($this)
            ->flatten()
            ->filter()
            ->keyBy(fn(Directory $directory) => $directory->pwd());
    }

}
