<?php

namespace Jasny\Meta;

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
        if ($value instanceof \DateTime) return $value->format('c');

        if (is_resource($value)) {
            trigger_error("Unable to cast a " . get_resource_type($value) . " resource to a string", E_USER_WARNING);
            return $value;
        }
        
        if (is_array($value)) {
            trigger_error("Unable to cast an array to a string", E_USER_WARNING);
            return $value;
        }
        
        if (is_object($value) && !method_exists($value, '__toString')) {
            trigger_error("Unable to cast a " . get_class($value).  " object to a string", E_USER_WARNING);
            return $value;
        }
        
        return (string)$value;
    }
    
    /**
     * Cast value to a boolean
     * 
     * @param mixed $value
     * @return boolean
     */
    protected static function castValueToBoolean($value)
    {
        if (is_resource($value)) {
            trigger_error("Unable to cast a " . get_resource_type($value) . " resource to a boolean", E_USER_WARNING);
            return $value;
        }
        
        if (is_object($value)) {
            trigger_error("Unable to cast a " . get_class($value) . " object to a boolean", E_USER_WARNING);
            return $value;
        }
        
        if (is_array($value)) {
            trigger_error("Unable to cast an array to a boolean", E_USER_WARNING);
            return $value;
        }
        
        if (is_string($value)) {
            if (in_array(strtolower($value), ['1', 'true', 'yes', 'on'])) return true;
            if (in_array(strtolower($value), ['', '0', 'false', 'no', 'off'])) return false;
            
            trigger_error("Unable to cast string \"$value\" to a boolean", E_USER_WARNING);
            return $value;
        }
        
        return (bool)$value;
    }
    
    /**
     * Cast value to an integer
     * 
     * @param mixed $value
     * @return int
     */
    protected static function castValueToInteger($value)
    {
        return static::castValueToNumber('integer', $value);
    }
    
    /**
     * Cast value to an integer
     * 
     * @param mixed $value
     * @return int
     */
    protected static function castValueToFloat($value)
    {
        return static::castValueToNumber('float', $value);
    }
    
    /**
     * Cast value to an integer
     * 
     * @param string $type   'integer' or 'float'
     * @param mixed  $value
     * @return int|float
     */
    protected static function castValueToNumber($type, $value)
    {
        if (is_resource($value)) {
            trigger_error("Unable to cast a " . get_resource_type($value) . " resource to a $type", E_USER_WARNING);
            return $value;
        }
        
        if (is_object($value)) {
            trigger_error("Unable to cast a " . get_class($value) . " object to a $type", E_USER_WARNING);
            return $value;
        }
        
        if (is_array($value)) {
            trigger_error("Unable to cast an array to a $type", E_USER_WARNING);
            return $value;
        }
        
        if (is_string($value)) {
            $value = trim($value);
            if (!is_numeric(trim($value)) && $value !== '') {
                trigger_error("Unable to cast string \"$value\" to a $type", E_USER_WARNING);
                return $value;
            }
        }
        
        settype($value, $type);
        return $value;
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
        $array = $value === '' ? [] : (array)$value;

        if (isset($subtype)) {
            foreach ($array as &$v) {
                $v = static::castValue($v, $subtype);
            }
        }
        
        return $array;
    }
    
    /**
     * Cast value to an object
     * 
     * @param mixed $value
     * @return object
     */
    protected static function castValueToObject($value)
    {
        if (is_resource($value)) {
            trigger_error("Unable to cast a " . get_resource_type($value) . " resource to an object", E_USER_WARNING);
            return $value;
        }
        
        if (is_scalar($value)) {
            trigger_error("Unable to cast a ". gettype($value) . " to an object.", E_USER_WARNING);
            return $value;
        }
        
        return (object)$value;
    }
    
    /**
     * Cast value to an object
     * 
     * @param mixed $value
     * @return object
     */
    protected static function castValueToResource($value)
    {
        trigger_error("Unable to cast a ". gettype($value) . " to a resource.", E_USER_WARNING);
        return $value;
    }
    
    /**
     * Cast value to a non-internal type
     * 
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    protected static function castValueToClass($value, $type)
    {
        if (!class_exists($type)) throw new \Exception("Invalid type '$type'");
        return new $type($value);
    }
}
