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
        $class = get_called_class();
        $cacheKey = "Meta:{$class}:FromAnnotations";
        
        if (Meta::cache()->has($cacheKey)) {
            return Meta::cache()->get($cacheKey);
        }
        
        $refl = new ReflectionClass($class);
        
        $factory = new Factory\Annotations();
        $meta = $factory->create($refl);
        
        Meta::cache()->set("Meta:{$class}:FromAnnotations", $meta);
        
        return $meta;
    }
}
