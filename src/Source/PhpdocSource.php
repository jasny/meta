<?php

declare(strict_types=1);

namespace Jasny\Meta\Source;

use Jasny\ReflectionFactory\ReflectionFactory;
use Jasny\PhpdocParser\PhpdocParser;
use ReflectionClass;
use ReflectionProperty;
use InvalidArgumentException;

/**
 * Class for getting class meta using doc-comment
 */
class PhpdocSource implements SourceInterface
{
    /**
     * Factory for creating reflection objects
     * @var ReflectionFactory
     **/
    protected $factory;

    /**
     * Doc-comment parser
     * @var PhpdocParser
     **/
    protected $parser;

    /**
     * Create an instance of class
     *
     * @param ReflectionFactory $factory
     * @param PhpdocParser $parser
     */
    public function __construct(ReflectionFactory $factory, PHPDocParser $parser)
    {
        $this->factory = $factory;
        $this->parser = $parser;
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
        $meta['@properties'] = $this->getPropertiesMeta($reflection);

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
        $doc = $reflection->getDocComment();
        $notations = $this->parser->parse($doc);

        return $notations;
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
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $item) {
            $doc = $item->getDocComment();
            $notations = $this->parser->parse($doc);

            if ($notations) {
                $meta[$item->getName()] = $notations;
            }
        }

        return $meta;
    }
}
