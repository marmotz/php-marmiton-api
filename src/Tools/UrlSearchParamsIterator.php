<?php

namespace MarmitonApi\Tools;

use Iterator;

class UrlSearchParamsIterator implements Iterator
{
    private array $list;

    private int $position = 0;

    public function __construct(array &$list)
    {
        $this->list = &$list;
    }

    public function current(): ?string
    {
        return isset($this->list[$this->position]) ? $this->list[$this->position][1] : null;
    }

    public function key(): ?string
    {
        return isset($this->list[$this->position]) ? $this->list[$this->position][0] : null;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }
}
