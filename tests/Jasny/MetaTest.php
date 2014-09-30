<?php

namespace Jasny;

require_once __DIR__ . '/../support/class.php';
require_once __DIR__ . '/../support/function.php';

/**
 * Tests for Jasny\Meta.
 * 
 * @package Test
 * @author Arnold Daniels
 */
class MetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Meta::fromAnnotations() for a class
     */
    public function testFromAnnotations_Class()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        $this->assertInstanceOf('Jasny\Meta', $meta);
        
        $this->assertSame(true, $meta['foo']);
        $this->assertSame("Hello world", $meta['bar']);
        $this->assertSame("22", $meta['blue']);
        
        $this->assertInstanceOf('Jasny\Meta', $meta->x);
        $this->assertInstanceOf('Jasny\Meta', $meta->y);
        $this->assertInstanceOf('Jasny\Meta', $meta->bike);
        $this->assertInstanceOf('Jasny\Meta', $meta->ball);

        $this->assertNull($meta->no);
        $this->assertNull($meta->nop);
        
        $this->assertSame('123', $meta->x['test']);
    }
    
    /**
     * Test Meta::fromAnnotations() for a property
     */
    public function testFromAnnotations_Property()
    {
        $metaX = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'x'));
        $this->assertInstanceOf('Jasny\Meta', $metaX);
        $this->assertSame('float', $metaX['var']);
        $this->assertSame('123', $metaX['test']);
        $this->assertSame(true, $metaX['required']);
        
        $metaY = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'y'));
        $this->assertInstanceOf('Jasny\Meta', $metaY);
        $this->assertSame('int', $metaY['var']);
    }
    
    /**
     * Test Meta::normalizeVar() for a property
     */
    public function testNormalizeVar_Proprety()
    {
        $metaBall = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'ball'));
        $this->assertInstanceOf('Jasny\Meta', $metaBall);
        $this->assertSame('Ball', $metaBall['var']);
        
        $metaBike = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'bike'));
        $this->assertInstanceOf('Jasny\Meta', $metaBike);
        $this->assertSame('MetaTest\Bike', $metaBike['var']);
    }
    
    /**
     * Test Meta::normalizeVar() for a metohd
     */
    public function testNormalizeVar_Method()
    {
        $meta = Meta::fromAnnotations(new \ReflectionMethod('MetaTest\FooBar', 'read'));
        $this->assertInstanceOf('Jasny\Meta', $meta);
        $this->assertSame('MetaTest\Book', $meta['return']);
    }
    
    
    /**
     * Test Meta::get()
     * 
     * @depends testFromAnnotations_Class
     */
    public function testGet()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        
        $this->assertSame(true, $meta->get('foo'));
        $this->assertSame("Hello world", $meta->get('bar'));
        $this->assertSame("22", $meta->get('blue'));
    }
    
    /**
     * Test Meta::get() for non existing items
     * 
     * @depends testFromAnnotations_Class
     */
    public function testGet_Nop()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        
        $this->assertNull($meta['nop']);
        $this->assertNull($meta->get('nop'));
    }
    
    /**
     * Test Meta::set()
     * 
     * @depends testFromAnnotations_Class
     */
    public function testSet()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        
        $meta->set('foo', false);
        $this->assertSame(false, $meta['foo']);
        
        $meta->set('cow', "moo");
        $this->assertSame("moo", $meta['cow']);

        $meta->set(['bar' => 'Goodbye', 'blue' => 99]);
        
        $this->assertSame(false, $meta['foo']);
        $this->assertSame("Goodbye", $meta['bar']);
        $this->assertSame(99, $meta['blue']);
        $this->assertSame("moo", $meta['cow']);
    }

    /**
     * Test Meta::ofProperties()
     * 
     * @depends testFromAnnotations_Class
     */
    public function testOfProperties()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        $props = $meta->ofProperties();
        
        $this->assertArrayHasKey('x', $props);
        $this->assertArrayHasKey('y', $props);
        $this->assertArrayHasKey('ball', $props);
        $this->assertArrayHasKey('bike', $props);
        
        $this->assertArrayNotHasKey('no', $props);
        
        $this->assertSame($meta->x, $props['x']);
        $this->assertSame($meta->y, $props['y']);
    }
}
