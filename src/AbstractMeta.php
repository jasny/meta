<?php

declare(strict_types=1);

namespace Jasny\Meta;

/**
 * Base class for meta classes
 */
abstract class AbstractMeta implements MetaInterface
{
    /**
     * Meta data
     * @var array
     */
    protected $meta = [];

    /**
     * Create class instance
     *
     * @param array $meta
     */
    public function __construct(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Get meta for specific key
     *
     * @param string $key
     * @param mixed $default
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
        return isset($this->meta[$key]) && (bool)$this->meta[$key];
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
