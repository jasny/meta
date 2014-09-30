<?php

namespace Jasny\Meta;

/**
 * Implementation for TypeCasting trait
 * @ignore
 */
class TypeCastingImpl implements Introspection
{
    use TypeCasting;

    private static $meta;
    public $prop;
    
    /**
     * @param string $type  Type for $this->prop
     */
    public function setType($type)
    {
        if (!isset($type)) {
            self::$meta = null;
            return;
        }
        
        self::$meta = new \Jasny\Meta();
        self::$meta->prop = new \Jasny\Meta(['var' => $type]);
    }
    
    /**
     * @return \Jasny\Meta
     */
    public static function meta()
    {
        return self::$meta;
    }
}
