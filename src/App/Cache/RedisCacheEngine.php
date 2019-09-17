<?php declare(strict_types = 1);

namespace App\Cache;

use App\Base\Settings;
use Redis;

class RedisCacheEngine implements CacheEngineInterface
{

    /** @var \Redis $redis */
    private $redis;

    /** @var \App\Base\Settings $settings */
    private $settings;
    
    public function __construct(Settings $settings)
    {
        $this->settings = $settings['redis'];
        $this->initialize();
    }

    private function initialize(): void
    {
        $this->redis = new Redis();

        $redisHost = $this->getConfig('host', 'redis');
        $this->redis->connect($redisHost, 6379);

        $prefix = $this->getConfig('prefix', 'CreditorWatch');
        $this->redis->setOption(Redis::OPT_PREFIX, $prefix);
    }

    /**
     * Try to get cache from redis
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @return mixed
     */
    public function obtain(string $key)
    {
        return $this->redis->get($key);
    }

    /**
     * Try to persis cache into redis
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @param mixed $data
     * @return bool
     */
    public function persist(string $key, $data): bool
    {
        $expire = $this->getConfig('expire', 36000);

        return $this->redis->setEx($key, $expire, $data);
    }

    /**
     * Try to read config from \App\Base\Settings
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function getConfig(string $key, $default = null)
    {
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }

        return $default;
    }
}
