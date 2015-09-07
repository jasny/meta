<?php

namespace Jasny;

/**
 * Metadata for a class, property or function
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
class Meta extends \ArrayObject
{
    /**
     * Meta data of class properties
     * @var Meta[]
     */
    protected $properties__;
    
    /**
     * Get metadata from annotations
     *
     * @param \Reflection $refl
     * @return static
     */
    public static function fromAnnotations(\Reflector $refl)
    {
        $meta = new static(static::parseDocComment($refl->getDocComment()));
        
        if ($refl instanceof \ReflectionClass) {
            static::addPropertyAnnotations($meta, $refl);
        } elseif ($refl instanceof \ReflectionProperty) {
            if (isset($meta['var'])) $meta['var'] = static::normalizeVar($refl, $meta['var']);
        } elseif ($refl instanceof \ReflectionMethod) {
            if (isset($meta['return'])) $meta['return'] = static::normalizeVar($refl, $meta['return']);
        }
        
        return $meta;
    }
    
    /**
     * Add metadata for properties of a class
     *
     * @param Meta             $meta
     * @param \ReflectionClass $refl
     */
    protected static function addPropertyAnnotations(Meta $meta, \ReflectionClass $refl)
    {
        $props = $refl->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            $name = $prop->getName();
            $ann = static::fromAnnotations($prop);
            
            $meta->$name = $ann;
        }
    }
    
    /**
     * Parse a docblock and extract annotations
     *
     * @param string $doc
     * @return array
     */
    protected static function parseDocComment($doc)
    {
        $ann = [];
        $matches = null;

        if (preg_match_all('/^\s*(?:\/\*)?\*\s*@(\S+)(?:[ \t]+(\S.*?))?(?:\*\*\/)?$/m', $doc, $matches, PREG_PATTERN_ORDER)) {
            $keys = $matches[1];
            $values = array_map(function ($v) {
                return trim($v) === '' ? true : trim($v);

            }, $matches[2]);
            $ann += array_combine($keys, $values);
        }
        
        return $ann;
    }
    
    /**
     * Clean/Normalize var annotation gotten through reflection
     *
     * @param \ReflectionProperty|\ReflectionMethod $refl
     * @param string                                $var
     * @return string
     */
    protected static function normalizeVar(\Reflector $refl, $var)
    {
        // Remove additional var info
        if (strpos($var, ' ') !== false) $var = substr($var, 0, strpos($var, ' '));

        // Normalize call types to global namespace
        $internalTypes = ['bool', 'boolean', 'int', 'integer', 'float', 'string', 'array', 'object'];
        if (isset($var) && !in_array($var, $internalTypes)) {
            if ($var[0] === '\\') {
                $var = substr($var, 1);
            } else {
                $ns = $refl->getDeclaringClass()->getNamespaceName();
                if ($ns) $var = $ns . '\\' . $var;
            }
        }
        
        return $var;
    }
    
    
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
        $values = is_array($key) ? $key : [$key => $value];
        
        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
    
    /**
     * Returns the value at the specified index
     * @link http://php.net/manual/en/arrayobject.offsetget.php
     *
     * @param mixed $index  The index with the value.
     * @return mixed The value at the specified index or NULL.
     */
    public function offsetGet($index)
    {
        return parent::offsetExists($index) ? parent::offsetGet($index) : null;
    }
    
    
    /**
     * Add property meta
     *
     * @param string $key
     * @param Meta  $meta
     */
    public function __set($key, Meta $meta)
    {
        $this->properties__[$key] = $meta;
    }
    
    /**
     * Get property meta
     *
     * @param string $key
     * @param Meta  $meta
     */
    public function __get($key)
    {
        return isset($this->properties__[$key]) ? $this->properties__[$key] : null;
    }

    /**
     * Get the metadata of a property
     *
     * @param string $property
     * @return array
     */
    public function of($property)
    {
        return $this->__get($property);
    }
    
    /**
     * Get the metadata of all the class properties
     *
     * @return array
     */
    public function ofProperties()
    {
        return $this->properties__;
    }
}
