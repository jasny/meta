<?php

namespace Jasny\Meta;

/**
 * Implementation for TypeCasting trait
 * @ignore
 */
class TypeCastingObject implements TypeCasting, Introspection
{
    use TypeCastingImplementation;

    private static $meta;
    public $prop;
    
    /**
     * @param string $type  Type for $this->prop
     */
    public static function setType($type)
    {
        if (!isset($type)) {
            self::$meta = null;
            return;
        }
        
        self::$meta = new \Jasny\Meta();
        self::$meta->ofProperty('prop')['var'] = $type;
    }
    
    /**
     * @return \Jasny\Meta
     */
    public static function meta()
    {
        return self::$meta;
    }
}
