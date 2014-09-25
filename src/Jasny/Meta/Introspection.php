<?php

namespace Jasny\Meta;

/**
 * Class supports getting metadata through introspection.
 * 
 * @author Arnold Daniels
 * @license http://jasny.net/mit MIT
 */
interface Introspection
{
    /**
     * Get metadata
     * 
     * @return \Jasny\Meta
     */
    public static function meta();
}
