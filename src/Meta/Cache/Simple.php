<?php

namespace Jasny\Meta\Cache;

use Jasny\Meta;
use Jasny\Meta\Cache;

/**
 * Very simple caching in the process memory
 */
class Simple implements Cache
{
    /**
     * @var array
     */
    protected $cache;
    
    /**
     * Store meta in cache
     * 
     * @param string $key
     * @param Meta   $meta
     */
    public function set($key, Meta $meta)
    {
        $this->cache[$key] = clone $meta;
    }
    
    /**
     * Get meta from cache
     * 
     * @param string $key
     * @return Meta|null
     */
    public function get($key)
    {
        return isset($this->cache[$key]) ? $this->cache[$key] : null;
    }
    
    /**
     * Check if meta exists in cache
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->cache[$key]);
    }
}
