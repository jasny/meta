<?php

namespace Jasny\Meta;

use Jasny\TypeCast;

/**
 * Cast class properties based on the meta data.
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
trait TypeCasting
{
    /**
     * Cast properties
     *
     * @return $this
     */
    public function cast()
    {
        foreach (static::meta()->ofProperties() as $name => $meta) {
            if (!isset($this->$name)) continue;
            
            if (isset($meta['var'])) {
                $this->$name = static::castValue($this->$name, $meta['var']);
            }
        }
        
        return $this;
    }
    
    
    /**
     * Cast the value to a type.
     *
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, $type)
    {
        if ($type === 'bool') $type = 'boolean';
        if ($type === 'int') $type = 'integer';
        
        // No casting needed
        if (is_null($value) || (is_object($value) && is_a($value, $type)) || gettype($value) === $type) {
            return $value;
        }
        
        // Cast internal types
        if (in_array($type, ['string', 'boolean', 'integer', 'float', 'array', 'object', 'resource'])) {
            return call_user_func([get_called_class(), 'to' . ucfirst($type)], $value);
        }

        // Cast to class
        return substr($type, -2) === '[]' ?
            static::toArray($value, substr($type, 0, -2)) :
            static::toClass($value, $type);
    }
    
    /**
     * Cast value to a string
     *
     * @param mixed $value
     * @return string
     */
    protected static function toString($value)
    {
        return TypeCast::toString($value);
    }
    
    /**
     * Cast value to a boolean
     *
     * @param mixed $value
     * @return boolean
     */
    protected static function toBoolean($value)
    {
        return TypeCast::toBoolean($value);
    }
    
    /**
     * Cast value to an integer
     *
     * @param mixed $value
     * @return int
     */
    protected static function toInteger($value)
    {
        return TypeCast::toInteger($value);
    }
    
    /**
     * Cast value to an integer
     *
     * @param mixed $value
     * @return int
     */
    protected static function toFloat($value)
    {
        return TypeCast::toFloat($value);
    }

    /**
     * Cast value to a typed array
     *
     * @param mixed  $value
     * @param string $subtype  Type of the array items
     * @return mixed
     */
    protected static function toArray($value, $subtype = null)
    {
        return TypeCast::toArray($value, $subtype);
    }
    
    /**
     * Cast value to an object
     *
     * @param mixed $value
     * @return object
     */
    protected static function toObject($value)
    {
        return TypeCast::toObject($value);
    }
    
    /**
     * Cast value to an object
     *
     * @param mixed $value
     * @return object
     */
    protected static function toResource($value)
    {
        return TypeCast::toResource($value);
    }
    
    /**
     * Cast value to a non-internal type
     *
     * @param mixed  $value
     * @param string $class
     * @return mixed
     */
    protected static function toClass($value, $class)
    {
        return TypeCast::toClass($value, $class);
    }
}
