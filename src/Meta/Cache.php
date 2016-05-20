<?php

namespace Jasny\Meta;

use Jasny\Meta;

/**
 * Caching Meta
 */
interface Cache
{
    /**
     * Store meta in cache
     * 
     * @param string $key
     * @param Meta   $meta
     */
    public function set($key, Meta $meta);
    
    /**
     * Get meta from cache
     * 
     * @param string $key
     * @return Meta|null
     */
    public function get($key);
    
    /**
     * Check if meta exists in cache
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key);
}
