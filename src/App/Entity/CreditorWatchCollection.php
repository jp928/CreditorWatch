<?php declare(strict_types = 1);

namespace App\Entity;

use Iterator;
use Serializable;

class CreditorWatchCollection implements Iterator, Serializable
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
        return isset($this->collection[$this->position]) && ($item instanceof CreditorWatch);
    }

    public function push(CreditorWatch $creditorWatch): void
    {
        $this->collection[] = $creditorWatch;

        $this->rewind();
    }

    public function size(): int
    {
        return count($this->collection);
    }

    public function serialize(): string
    {
        return serialize($this->collection);
    }
    
    /**
     * Unserialize
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param mixed $data
     * @return void
     */
    public function unserialize($data): void
    {
        $this->collection = unserialize($data);
    }
}
