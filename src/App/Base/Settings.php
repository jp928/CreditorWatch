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

    public function &__get($key)
    {
        return $this->settings[$key];
    }

        /**
     * Assigns a value to the specified offset
     *
     * @param string The offset to assign the value to
     * @param mixed  The value to set
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
   *
   * @param string An offset to check for
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
   *
   * @param string The offset to unset
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
   *
   * @param string The offset to retrieve
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
