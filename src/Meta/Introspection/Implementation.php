<?php

namespace Jasny\Meta;

use ReflectionClass;
use Jasny\Meta;

/**
 * Get class metadata through annotations
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
trait IntrospectionImplementation
{
    /**
     * Get metadata
     *
     * @return Meta
     */
    public static function meta()
    {
        $refl = new ReflectionClass(get_called_class());
        return Meta::from($refl);
    }
}
