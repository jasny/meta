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
trait TypeCastingImplementation
{
    /**
     * Get type cast object
     * 
     * @return Jasny\TypeCast
     */
    protected function typeCast($value)
    {
        $typecast = TypeCast::value($value);
        
        $typecast->alias('self', get_class($this));
        $typecast->alias('static', get_class($this));
        
        return $typecast;
    }
    
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
                $this->$name = $this->typeCast($this->$name)->to($meta['var']);
            }
        }
        
        return $this;
    }
}
