<?php

declare(strict_types=1);

namespace Jasny\Meta;

/**
 * Class holding meta data of a specific class
 */
class MetaClass extends AbstractMeta
{
    /**
     * Class properties
     * @var array
     **/
    protected $properties;

    /**
     * Create class instance
     *
     * @param array $meta
     */
    public function __construct(array $meta)
    {
        $this->properties = $this->castPropertiesMeta($meta);
        unset($meta['@properties']);

        parent::__construct($meta);
    }

    /**
     * Cast properties meta to MetaProperty class
     *
     * @param array $meta
     * @return array
     */
    protected function castPropertiesMeta(array $meta): array
    {
        $properties = [];
        foreach ($meta['@properties'] ?? [] as $name => $data) {
            $properties[$name] = new MetaProperty($data);
        }

        return $properties;
    }

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
    public function getProperties(): array
    {
        return $this->properties ?? [];
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
