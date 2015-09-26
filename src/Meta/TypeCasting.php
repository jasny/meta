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
        
        // Cast internal types
        if (in_array($type, ['string', 'boolean', 'integer', 'float', 'array', 'object', 'resource'])) {
            return call_user_func([get_called_class(), 'castValueTo' . ucfirst($type)], $value);
        }

        // Cast to class
        return substr($type, -2) === '[]' ?
            static::castValueToArray($value, substr($type, 0, -2)) :
            static::castValueToClass($value, $type);
    }
    
    /**
     * Cast value to a string
     *
     * @param mixed $value
     * @return string
     */
    protected static function castValueToString($value)
    {
        return TypeCast::toString($value);
    }
    
    /**
     * Cast value to a boolean
     *
     * @param mixed $value
     * @return boolean
     */
    protected static function castValueToBoolean($value)
    {
        return TypeCast::toBoolean($value);
    }
    
    /**
     * Cast value to an integer
     *
     * @param mixed $value
     * @return int
     */
    protected static function castValueToInteger($value)
    {
        return TypeCast::toInteger($value);
    }
    
    /**
     * Cast value to an integer
     *
     * @param mixed $value
     * @return int
     */
    protected static function castValueToFloat($value)
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
    protected static function castValueToArray($value, $subtype = null)
    {
        if (is_null($value)) return null;
    
        $cast = null;
        if (isset($subtype)) {
            $cast = function($value) use($subtype) {
                return static::castValue($value, $subtype);
            };
        }
        
        return TypeCast::toArray($value, $cast);
    }
    
    /**
     * Cast value to an object
     *
     * @param mixed $value
     * @return object
     */
    protected static function castValueToObject($value)
    {
        return TypeCast::toObject($value);
    }
    
    /**
     * Cast value to an object
     *
     * @param mixed $value
     * @return object
     */
    protected static function castValueToResource($value)
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
    protected static function castValueToClass($value, $class)
    {
        return TypeCast::toClass($value, $class);
    }
}

