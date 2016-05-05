<?php

namespace Jasny;

use Jasny\Meta;

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
    public function testFromAnnotationsForClass()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        $this->assertInstanceOf(Meta::class, $meta);
        
        $this->assertSame(true, $meta['foo']);
        $this->assertSame("Hello world", $meta['bar']);
        $this->assertSame("22", $meta['blue']);
        
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('x'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('y'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('bike'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('ball'));

        $this->assertNull($meta->ofProperty('no'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('nop'));
        
        $this->assertSame('123', $meta->ofProperty('x')['test']);
    }
    
    /**
     * Test Meta::fromAnnotations() for a property
     */
    public function testFromAnnotationsForProperty()
    {
        $metaX = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'x'));
        $this->assertInstanceOf(Meta::class, $metaX);
        $this->assertSame('float', $metaX['var']);
        $this->assertSame('123', $metaX['test']);
        $this->assertSame(true, $metaX['required']);
        
        $metaY = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'y'));
        $this->assertInstanceOf(Meta::class, $metaY);
        $this->assertSame('int', $metaY['var']);
    }
    
    /**
     * Test Meta::normalizeVar() for a property
     */
    public function testNormalizeVarForProprety()
    {
        $metaBall = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'ball'));
        $this->assertInstanceOf(Meta::class, $metaBall);
        $this->assertSame('Ball', $metaBall['var']);
        
        $metaBike = Meta::fromAnnotations(new \ReflectionProperty('MetaTest\FooBar', 'bike'));
        $this->assertInstanceOf(Meta::class, $metaBike);
        $this->assertSame('MetaTest\Bike', $metaBike['var']);
    }
    
    /**
     * Test Meta::normalizeVar() for a metohd
     */
    public function testNormalizeVarForMethod()
    {
        $meta = Meta::fromAnnotations(new \ReflectionMethod('MetaTest\FooBar', 'read'));
        $this->assertInstanceOf(Meta::class, $meta);
        $this->assertSame('MetaTest\Book', $meta['return']);
    }
    
    
    /**
     * Test Meta::get()
     * 
     * @depends testFromAnnotationsForClass
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
     * @depends testFromAnnotationsForClass
     */
    public function testGetNop()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        
        $this->assertNull($meta['nop']);
        $this->assertNull($meta->get('nop'));
    }
    
    /**
     * Test Meta::set()
     * 
     * @depends testFromAnnotationsForClass
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
     * @depends testFromAnnotationsForClass
     */
    public function testOfProperties()
    {
        $meta = Meta::fromAnnotations(new \ReflectionClass('MetaTest\FooBar'));
        $props = $meta->ofProperties();
        
        $this->assertArrayHasKey('x', $props);
        $this->assertArrayHasKey('y', $props);
        $this->assertArrayHasKey('ball', $props);
        $this->assertArrayHasKey('bike', $props);
        
        $this->assertInstanceOf(Meta::class, $props['x']);
        $this->assertInstanceOf(Meta::class, $props['y']);
        $this->assertInstanceOf(Meta::class, $props['ball']);
        $this->assertInstanceOf(Meta::class, $props['bike']);
        
        $this->assertArrayHasKey('no', $props);
        $this->assertNull($props['no']);
        
        $this->assertArrayNotHasKey('nop', $props);
        
        $this->assertSame($meta->ofProperty('x'), $props['x']);
        $this->assertSame($meta->ofProperty('y'), $props['y']);
    }
}
