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
        // No casting needed
        if (is_null($value) || (is_object($value) && is_a($value, $type)) || gettype($value) === $type) {
            return $value;
        }
        
        // Cast
        switch ($type) {
            case 'string':
                if ($value instanceof \DateTime) $value = $value->format('c');
            case 'bool': case 'boolean':
            case 'int':  case 'integer':
            case 'float':
                settype($value, $type);
                return $value;
                
            case 'array':
                return $value === '' ? [] : (array)$value;
            
            case 'object':
                if ($value === '') $value = [];
                if (is_scalar($value)) trigger_error("Casting a ". gettype($value) . " to an object.", E_USER_NOTICE);
                return (object)$value;
            
            default:
                if (substr($type, -2) === '[]') return static::castValueTypedArray($value, substr($type, 0, -2));
                return static::castValueClass($value, $type);
        }
    }

    /**
     * Cast value to a typed array
     * 
     * @param mixed  $value
     * @param string $subtype  Type of the array items
     * @return mixed
     */
    protected static function castValueTypedArray($value, $subtype)
    {
        $array = $value === '' ? [] : (array)$value;

        foreach ($array as &$v) {
            $v = static::castValue($v, $subtype);
        }
        
        return $array;
    }
    
    /**
     * Cast value to a non-internal type
     * 
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    protected static function castValueClass($value, $type)
    {
        if (!class_exists($type)) throw new \Exception("Invalid type '$type'");
        return new $type($value);
    }
}
