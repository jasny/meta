<?php

namespace Jasny\Meta\Tests;

use Jasny\Meta\MetaClass;
use Jasny\Meta\MetaProperty;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\MetaClass
 * @covers Jasny\Meta\AbstractMeta
 */
class MetaClassTest extends TestCase
{
    /**
     * Provide data for testing 'get' method
     *
     * @return array
     */
    public function getProvider()
    {
        return [
            [[], '', null],
            [[], 'foo', null],
            [['bar' => 'zoo'], 'foo', null],
            [['bar' => 'zoo', 'foo' => 'test'], 'foo', 'test'],
            [['properties' => 'test'], 'properties', 'test'],
            [['@properties' => ['test' => []]], '@properties', null]
        ];
    }

    /**
     * Test 'get' method
     *
     * @dataProvider getProvider
     */
    public function testGet($data, $key, $expected)
    {
        $meta = new MetaClass($data);
        $result = $meta->get($key);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'get' method with default value
     */
    public function testGetDefault()
    {
        $data = ['foo' => 'zoo'];

        $meta = new MetaClass($data);
        $result = $meta->get('bar', 'some_default');

        $this->assertSame('some_default', $result);
    }

    /**
     * Provide data for testing 'is' method
     *
     * @return array
     */
    public function isProvider()
    {
        return [
            [[], 'kar', false],
            [['bar' => 'zoo', 'foo' => 'test'], 'kar', false],
            [['bar' => 'zoo', 'kar' => false], 'kar', false],
            [['bar' => 'zoo', 'kar' => 0], 'kar', false],
            [['bar' => 'zoo', 'kar' => []], 'kar', false],
            [['bar' => 'zoo', 'kar' => null], 'kar', false],
            [['bar' => 'zoo', 'kar' => 'test'], 'kar', true]
        ];
    }

    /**
     * Test 'is' method
     *
     * @dataProvider isProvider
     */
    public function testIs($data, $key, $expected)
    {
        $meta = new MetaClass($data);
        $result = $meta->is($key);

        $this->assertSame($expected, $result);
    }

    /**
     * Provide data for testing 'has' method
     *
     * @return array
     */
    public function hasProvider()
    {
        return [
            [[], 'kar', false],
            [['bar' => 'zoo', 'foo' => 'test'], 'kar', false],
            [['bar' => 'zoo', 'kar' => false], 'kar', true],
            [['bar' => 'zoo', 'kar' => 0], 'kar', true],
            [['bar' => 'zoo', 'kar' => []], 'kar', true],
            [['bar' => 'zoo', 'kar' => null], 'kar', true],
            [['bar' => 'zoo', 'kar' => 'test'], 'kar', true]
        ];
    }

    /**
     * Test 'has' method
     *
     * @dataProvider hasProvider
     */
    public function testHas($data, $key, $expected)
    {
        $meta = new MetaClass($data);
        $result = $meta->has($key);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'getProperty' method
     */
    public function testGetProperty()
    {
        $data = [
            'properties' => [
                'foo' => ['bar' => 'invalid_value']
            ],
            '@properties' => [
                'foo' => ['bar' => 'value']
            ]
        ];

        $meta = new MetaClass($data);
        $result = $meta->getProperty('foo');

        $this->assertInstanceOf(MetaProperty::class, $result);
        $this->assertSame('value', $result->get('bar'));
    }

    /**
     * Test 'getProperty' method, if no properties are set
     */
    public function testGetPropertyNoProperties()
    {
        $data = [
            'foo' => 'bar',
            'properties' => ['foo' => 'bar2']
        ];

        $meta = new MetaClass($data);
        $result = $meta->getProperty('foo');

        $this->assertSame(null, $result);
    }

    /**
     * Test 'getProperties' method
     */
    public function testGetProperties()
    {
        $data = [
            'properties' => [
                'foo' => ['bar' => 'invalid_value']
            ],
            '@properties' => [
                'foo' => ['bar' => 'value'],
                'zoo' => ['test' => 'rest']
            ]
        ];

        $meta = new MetaClass($data);
        $result = $meta->getProperties();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(MetaProperty::class, $result['foo']);
        $this->assertInstanceOf(MetaProperty::class, $result['zoo']);
        $this->assertSame('value', $result['foo']->get('bar'));
        $this->assertSame('rest', $result['zoo']->get('test'));
    }
}
