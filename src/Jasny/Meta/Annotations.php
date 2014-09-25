<?php

namespace Jasny\Meta;

/**
 * Get class meta data through annotations
 * 
 * @author Arnold Daniels
 * @license http://jasny.net/mit MIT
 */
trait Annotations
{
    /**
     * Get metadata
     * 
     * @return \Jasny\Meta
     */
    public static function meta()
    {
        $refl = new \ReflectionClass(get_called_class());
        return \Jasny\Meta::fromAnnotations($refl);
    }
}
