<?php

namespace Jasny\Meta\Cache;

use Jasny\Meta\Cache;
use Jasny\Meta;

/**
 * Don't cache meta
 */
class None implements Cache
{
    /**
     * No caching here
     * 
     * @param string $key
     * @param Meta   $meta
     */
    public function set($key, Meta $meta)
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
    public function has($key)
    {
        return false;
    }
}
