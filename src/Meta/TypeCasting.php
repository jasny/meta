<?php

namespace Jasny\Meta;

/**
 * Class supports type casting of properties.
 *
 * @author  Arnold Daniels <arnold@jasny.net>
 * @license https://raw.github.com/jasny/meta/master/LICENSE MIT
 * @link    https://jasny.github.com/meta
 */
interface TypeCasting
{
    /**
     * Cast properties
     *
     * @return $this
     */
    public function cast();
}
