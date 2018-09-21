<?php

declare(strict_types=1);

namespace Jasny\Meta\Cache;

use Jasny\Meta\MetaClass;
use Jasny\Meta\CacheInterface;

/**
 * Very simple caching in the process memory
 */
class Simple implements CacheInterface
{
    /**
     * @var array
     */
    protected $cache;

    /**
     * Store meta in cache
     *
     * @param string      $key
     * @param MetaClass   $meta
     * @return void
     */
    public function set($key, MetaClass $meta): void
    {
        $this->cache[$key] = clone $meta;
    }

    /**
     * Get meta from cache
     *
     * @param string $key
     * @return MetaClass|null
     */
    public function get($key): ?MetaClass
    {
        return isset($this->cache[$key]) ? $this->cache[$key] : null;
    }

    /**
     * Check if meta exists in cache
     *
     * @param string $key
     * @return boolean
     */
    public function has($key): bool
    {
        return isset($this->cache[$key]);
    }
}
