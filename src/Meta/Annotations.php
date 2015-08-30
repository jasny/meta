<?php

namespace Jasny\Meta;

/**
 * Get class metadata through annotations
 * 
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
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
