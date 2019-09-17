<?php declare (strict_types = 1);

namespace App\Entity;

class CreditorWatch
{

    /** @var string $key */
    private $key;

    /** @var string $value */
    private $value;

    /**
     * Setter for key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Setter for value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Getter for key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Getter for value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
