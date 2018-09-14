<?php

declare(strict_types=1);

namespace Jasny\Meta;

/**
 * Interface for meta classes
 */
interface MetaInterface
{
    /**
     * Get meta for specific key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Check if meta for given key exists and is not false
     *
     * @param string $key
     * @return bool
     */
    public function is(string $key): bool;

    /**
     * Check if given key exists in meta data
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
}
