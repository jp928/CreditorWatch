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
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @return mixed
     */
    public function obtain(string $key);

    /**
     * Persist result to cache
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function persist(string $key, $value): bool;
}
