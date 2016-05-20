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
     * @return Meta|null
     */
    public function get($key)
    {
        return null;
    }
}
