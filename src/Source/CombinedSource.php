<?php

declare(strict_types=1);

namespace Jasny\Meta\Source;

use Jasny\ReflectionFactory\ReflectionFactory;
use Jasny\PhpdocParser\PhpdocParser;
use ReflectionClass;
use ReflectionProperty;
use InvalidArgumentException;

/**
 * Class for getting class meta by merging meta, obtained from other sources
 */
class CombinedSource implements SourceInterface
{
    /**
     * Meta sources
     * @var array
     **/
    protected $sources;

    /**
     * Create an instance of class
     *
     * @param array $sources
     */
    public function __construct(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Obtain meta data for class as array
     *
     * @throws InvalidArgumentException If class does not exist
     * @param string $class
     * @return array
     */
    public function forClass(string $class): array
    {
        $meta = [];

        foreach ($this->sources as $source) {
            $sourceMeta = $source->forClass($class);
            $meta = $this->mergeMeta($meta, $sourceMeta);
        }

        return $meta;
    }

    /**
     * Merge meta data
     *
     * @param array $meta
     * @param array $sourceMeta
     * @return array
     */
    protected function mergeMeta(array $meta, array $sourceMeta): array
    {
        $properties = $meta['properties'] ?? [];
        $sourceProperties = $sourceMeta['properties'] ?? [];

        foreach ($sourceProperties as $name => $data) {
            $properties[$name] = array_merge($properties[$name] ?? [], $data);
        }

        $meta = array_merge($meta, $sourceMeta);
        $meta['properties'] = $properties;

        return $meta;
    }
}
