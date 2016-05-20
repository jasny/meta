<?php

namespace Jasny\Meta;

use Jasny\Meta;
use Jasny\Meta\TypeCasting;
use Jasny\Meta\Introspection;

/**
 * Implementation for TypeCasting trait
 * @ignore
 */
class TypeCastingObject implements TypeCasting, Introspection
{
    use TypeCasting\Implementation;

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
        
        self::$meta = new Meta();
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
