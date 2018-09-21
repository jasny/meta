<?php

namespace Jasny\Meta\Tests;

use Jasny\Meta\Factory;
use Jasny\Meta\MetaClass;
use Jasny\Meta\Source\SourceInterface;
use Jasny\Meta\Cache\CacheInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\Factory
 */
class FactoryTest extends TestCase
{
    /**
     * Test 'forClass' method
     */
    public function testForClass()
    {
        $class = 'Foo';
        $cacheName = 'MetaForClass:' . $class;

        $source = $this->createMock(SourceInterface::class);
        $cache = $this->createMock(CacheInterface::class);

        $cache->expects($this->once())->method('get')->with($cacheName)->willReturn(null);
        $source->expects($this->once())->method('forClass')->with($class)->willReturn(['foo' => 'bar']);
        $cache->expects($this->once())->method('set')->with($cacheName, $this->callback(function ($meta) {
            return $meta instanceof MetaClass && $meta->get('foo') === 'bar';
        }));

        $factory = new Factory($source, $cache);
        $result = $factory->forClass($class);

        $this->assertInstanceOf(MetaClass::class, $result);
        $this->assertSame('bar', $result->get('foo'));
    }

    /**
     * Test 'forClass' method if value is obtained from cache
     */
    public function testForClassCached()
    {
        $class = 'Foo';

        $source = $this->createMock(SourceInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $meta = $this->createMock(MetaClass::class);

        $cache->expects($this->once())->method('get')->with('MetaForClass:' . $class)->willReturn($meta);
        $source->expects($this->never())->method('forClass')->with($class);

        $factory = new Factory($source, $cache);
        $result = $factory->forClass($class);

        $this->assertSame($meta, $result);
    }
}
