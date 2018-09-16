<?php

namespace Jasny\Meta\Tests;

use Jasny\Meta\MetaProperty;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\MetaProperty
 * @covers Jasny\Meta\AbstractMeta
 */
class MetaPropertyTest extends TestCase
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
            [['bar' => 'zoo', 'foo' => 'test'], 'foo', 'test']
        ];
    }

    /**
     * Test 'get' method
     *
     * @dataProvider getProvider
     */
    public function testGet($data, $key, $expected)
    {
        $meta = new MetaProperty($data);
        $result = $meta->get($key);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'get' method with default value
     */
    public function testGetDefault()
    {
        $data = ['foo' => 'zoo'];

        $meta = new MetaProperty($data);
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
        $meta = new MetaProperty($data);
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
        $meta = new MetaProperty($data);
        $result = $meta->has($key);

        $this->assertSame($expected, $result);
    }
}
