<?php

namespace Jasny\Meta;

/**
 * Class supports getting metadata through introspection.
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
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
