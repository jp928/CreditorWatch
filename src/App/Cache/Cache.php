<?php declare(strict_types = 1);

namespace App\Cache;

class Cache implements CacheInterface
{

    /** @var \App\Cache\CacheInterface $cacheEngine */
    private $cacheEngine;
  
    public function __construct(CacheEngineInterface $cacheEngine)
    {
        $this->cacheEngine = $cacheEngine;
    }

    public function obtain(): void
    {
      $cacheEngine->obtain();
    }
}
