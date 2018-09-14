<?php

namespace Jasny\Meta\TypeCasting;

use Jasny\TypeCast;

/**
 * Cast class properties based on the meta data.
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
trait Implementation
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
                $typecast = $this->typeCast($this->$name)->setName(get_class($this) . '::' . $name);
                $this->$name = $typecast->to($meta['var']);
            }
        }
        
        return $this;
    }
}
