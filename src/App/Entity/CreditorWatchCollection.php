<?php declare(strict_types = 1);

namespace App\Entity;

use Iterator;

class CreditorWatchCollection implements Iterator
{

    /** @var int $position */
    private $position = 0;

    /** @var \App\Entity\CreditorWatch[] $collection */
    private $collection = [];

    public function __construct()
    {
        $this->position = 0;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): CreditorWatch
    {
        return $this->collection[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        $item = $this->collection[$this->position];

        return isset($item) && ($item instanceof CreditorWatch);
    }

    public function push(CreditorWatch $creditorWatch): void
    {
        $this->collection[] = $creditorWatch;

        $this->rewind();
    }
}
