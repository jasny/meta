<?php

declare(strict_types=1);

namespace Jasny\Meta\Source;

use Jasny\ReflectionFactory\ReflectionFactory;
use function Jasny\uncase;
use ReflectionClass;
use ReflectionProperty;
use InvalidArgumentException;

/**
 * Class for getting class meta using reflection
 */
class ReflectionSource implements SourceInterface
{
    /**
     * Factory for creating reflection objects
     * @var ReflectionFactory
     **/
    protected $factory;

    /**
     * Create an instance of class
     *
     * @param ReflectionFactory $factory
     */
    public function __construct(ReflectionFactory $factory)
    {
        $this->factory = $factory;
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
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Cannot get meta for non-exist class '$class'");
        }

        $reflection = $this->factory->reflectClass($class);

        $meta = $this->getClassMeta($reflection);
        $meta['properties'] = $this->getPropertiesMeta($reflection);

        return $meta;
    }

    /**
     * Get meta for class
     *
     * @param ReflectionClass $reflection
     * @return array
     */
    protected function getClassMeta(ReflectionClass $reflection): array
    {
        return [
            'name' => $reflection->getName(),
            'title' => uncase($reflection->getShortName())
        ];
    }

    /**
     * Get meta data for class properties
     *
     * @param ReflectionClass $reflection
     * @return array
     */
    protected function getPropertiesMeta(ReflectionClass $reflection): array
    {
        $meta = [];
        $defaults = $reflection->getDefaultProperties();
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $item) {
            $name = $item->getName();
            $meta[$name] = [
                'name' => $name,
                'title' => uncase($name)
            ];

            if (array_key_exists($name, $defaults)) {
                $meta[$name]['default'] = $defaults[$name];
            }
        }

        return $meta;
    }
}
