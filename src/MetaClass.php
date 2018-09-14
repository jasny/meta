<?php

declare(strict_types=1);

namespace Jasny\Meta;

/**
 * Class holding meta data of a specific class
 */
class MetaClass extends AbstractMeta
{
    /**
     * Array with meta-data for class properties
     * @var string
     **/
    protected $properties = [];

    /**
     * Get meta data for class property
     *
     * @param string $name
     * @return MetaProperty|null
     */
    public function getProperty(string $name): ?MetaProperty
    {
        return $this->properties[$name] ?? null;
    }

    /**
     * Get meta for all class properties
     *
     * @return array
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * Get meta for given class method
     *
     * @param string $name
     * @return mixed
     */
    public function getMethod(string $name)
    {

    }

    /**
     * Get meta for all class methods
     *
     * @return array|null
     */
    public function getMethods(): ?array
    {

    }

    /**
     * Get meta for class constant
     *
     * @param string $name
     * @return mixed
     */
    public function getConstant(string $name)
    {

    }

    /**
     * Get meta for all class constants
     *
     * @return array|null
     */
    public function getConstants(): ?array
    {

    }
}
