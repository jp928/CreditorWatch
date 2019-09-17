<?php declare(strict_types = 1);

namespace App\Cache;

/**
 * Interface CacheEngineInterface
 *
 * @package App\Cache
 */
interface CacheEngineInterface
{

    /**
     * Obtain result from cache
     *
     * @return void
     */
    public function obtain(string $key);

    /**
     * Persist result to cache
     *
     * @return bool
     */
    public function persist(): bool;
}
