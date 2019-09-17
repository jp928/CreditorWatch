<?php declare(strict_types = 1);

namespace App\Cache;

use function is_string;
use function serialize;
use function unserialize;

class Cache
{

    /** @var \App\Cache\CacheEngineInterface $cacheEngine */
    private $cacheEngine;
  
    public function __construct(CacheEngineInterface $cacheEngine)
    {
        $this->cacheEngine = $cacheEngine;
    }

    /**
     * Fetch data from cache engine
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @return mixed|null
     */
    public function obtain(string $key)
    {
        $cache = $this->cacheEngine->obtain($key);

        if (is_string($cache)) {
            return unserialize($cache);
        }

        return null;
    }

    /**
     * Persist data into cache engine
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    public function persist(string $key, $data): bool
    {
        if (is_string($data) === false) {
            $data = serialize($data);
        }

        return $this->cacheEngine->persist($key, $data);
    }
}
