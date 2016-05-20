<?php

namespace Jasny\Meta\Introspection;

use ReflectionClass;
use Jasny\Meta;
use Jasny\Meta\Factory;

/**
 * Get class metadata through annotations
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
trait AnnotationsImplementation
{
    /**
     * Get metadata
     *
     * @return Meta
     */
    public static function meta()
    {
        
        $factory = new Factory\Annotations();
        
        $refl = new ReflectionClass(get_called_class());
        return $factory->create($refl);
    }
}
