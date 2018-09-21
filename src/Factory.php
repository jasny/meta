<?php

declare(strict_types=1);

namespace Jasny\Meta;

use Jasny\Meta\Source\SourceInterface;
use Jasny\Meta\Cache\CacheInterface;

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
     *
     * @param  string $class
     * @return MetaClass
     */
    public function forClass(string $class): MetaClass
    {
        $cacheName = 'MetaForClass:' . $class;
        $meta = $this->cache->get($cacheName);

        if (!$meta instanceof MetaClass) {
            $data = $this->source->forClass($class);
            $meta = $this->asMeta($data);

            $this->cache->set($cacheName, $meta);
        }

        return $meta;
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
