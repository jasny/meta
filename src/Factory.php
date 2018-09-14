<?php

namespace Jasny\Meta;

use Jasny\Meta\Source\SourceInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Factory for getting meta
 */
class Factory implements FactoryInterface
{
    /**
     * Source for getting meta
     * @var SourceInterface
     **/
    protected $source;

    /**
     * Cache object for caching meta
     * @var CacheInterface
     **/
    protected $cache;

    /**
     * Create factory instance
     *
     * @param SourceInterface $source
     * @param CacheInterface $cache
     */
    public function __construct(SourceInterface $source, CacheInterface $cache)
    {
        $this->source = $source;
        $this->cache = $cache;
    }

    /**
     * Get meta for given class
     * @param  string $class
     * @return MetaClass
     */
    public function forClass(string $class): MetaClass
    {
        $data = $this->source->forClass($class);

        return $this->asMeta($data);
    }

    /**
     * Convert data to meta object
     *
     * @param array $data
     * @return MetaClass
     */
    protected function asMeta(array $data): MetaClass
    {
        return new MetaClass($data);
    }
}
