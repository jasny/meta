<?php

declare(strict_types=1);

namespace Jasny\Meta\Cache;

use Jasny\Meta\MetaClass;

/**
 * Don't cache meta
 */
class None implements CacheInterface
{
    /**
     * No caching here
     *
     * @param string      $key
     * @param MetaClass   $meta
     * @return void
     */
    public function set($key, MetaClass $meta): void
    {
    }

    /**
     * Nothing is cached
     *
     * @param string $key
     * @return null
     */
    public function get($key)
    {
        return null;
    }

    /**
     * No, it's not cached
     *
     * @param string $key
     * @return false
     */
    public function has($key): bool
    {
        return false;
    }
}
