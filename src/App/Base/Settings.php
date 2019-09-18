<?php declare(strict_types = 1);

namespace App\Base;

use ArrayAccess;

class Settings implements ArrayAccess
{

    /** @var string[] $settings */
    private $settings = [
      'redis' => [
        'expire' => 36000,
        'prefix' => 'CreditorWatch',
        'host' => 'redis',
      ],
    ];

    /**
     * Magic function to get property value
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param mixed $key
     * @return mixed
     */
    public function &__get($key)
    {
        return $this->settings[$key];
    }

    /**
     * Assigns a value to the specified offset
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param mixed $offset The offset to assign the value to
     * @param mixed $value The value to set
     * @access public
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->settings[] = $value;
        } else {
            $this->settings[$offset] = $value;
        }
    }

  /**
   * Whether or not an offset exists
   * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
   * @param mixed $offset An offset to check for
   * @access public
   * @return bool
   * @abstracting ArrayAccess
   */
    public function offsetExists($offset): bool
    {
        return isset($this->settings[$offset]);
    }

  /**
   * Unsets an offset
   * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
   * @param mixed $offset The offset to unset
   * @access public
   * @abstracting ArrayAccess
   */
    public function offsetUnset($offset): void
    {
        if (!$this->offsetExists($offset)) {
            return;
        }

        unset($this->settings[$offset]);
    }

  /**
   * Returns the value at specified offset
   * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
   * @param mixed $offset The offset to retrieve
   * @access public
   * @return mixed
   * @abstracting ArrayAccess
   */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset)
          ? $this->settings[$offset]
          : null;
    }
}
