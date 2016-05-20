<?php

namespace Jasny;

use ArrayObject;

use Jasny\Meta\Factory;
use Jasny\Meta\Cache;

/**
 * Metadata for a class, property or function
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
class Meta extends ArrayObject
{
    /**
     * Meta data of class properties
     * @var Meta[]
     */
    protected $properties = [];
    
    
    /**
     * Get metadata
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }
    
    /**
     * Set metadata
     *
     * @param string|array $key    Key or data as associated array
     * @param mixed        $value
     */
    public function set($key, $value = null)
    {
        $values = is_string($key) ? [$key => $value] : $key;
        
        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
    
    /**
     * Returns the value at the specified index
     * @see http://php.net/manual/en/arrayobject.offsetget.php
     *
     * @param mixed $index  The index with the value.
     * @return mixed The value at the specified index or NULL.
     */
    public function offsetGet($index)
    {
        return $this->offsetExists($index) ? parent::offsetGet($index) : null;
    }
    

    /**
     * Get the metadata of a property.
     * Will return null for a private or protected property
     *
     * @param string $property
     * @return array|null
     */
    public function ofProperty($property)
    {
        if (!array_key_exists($property, $this->properties)) {
            $this->properties[$property] = new Meta();
        }
        
        return $this->properties[$property];
    }
    
    /**
     * Get the metadata of all the class properties
     *
     * @return array
     */
    public function ofProperties()
    {
        return $this->properties;
    }
    
    /**
     * Deep cloning
     */
    public function __clone()
    {
        foreach ($this->properties as &$property) {
            $property = clone $property;
        }
    }
}
