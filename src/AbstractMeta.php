<?php

declare(strict_types=1);

namespace Jasny\Meta;

/**
 * Base class for meta classes
 */
abstract class AbstractMeta implements MetaInterface
{
    protected $meta = [];

    /**
     * Get meta for specific key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }

    /**
     * Check if meta for given key exists and is not false
     *
     * @param string $key
     * @return bool
     */
    public function is(string $key): bool
    {
        return !empty($this->meta[$key]);
    }

    /**
     * Check if given key exists in meta data
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->meta);
    }
}
