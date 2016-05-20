<?php

namespace Jasny;

use Jasny\Meta;
use Jasny\Meta\Factory;
use Jasny\Meta\Cache;
use Jasny\Meta\Test\FooBar;

/**
 * Tests for Jasny\Meta.
 * 
 * @package Test
 * @author Arnold Daniels
 */
class MetaTest extends \PHPUnit_Framework_TestCase
{
    protected function createMeta(\Reflector $refl)
    {
        $factory = new Factory\Annotations();
        return $factory->create($refl);
    }

    /**
     * Test $this->createMeta() for a class
     */
    public function testFromAnnotationsForClass()
    {
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        $this->assertInstanceOf(Meta::class, $meta);
        
        $this->assertSame(true, $meta['foo']);
        $this->assertSame("Hello world", $meta['bar']);
        $this->assertSame("22", $meta['blue']);
        
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('x'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('y'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('bike'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('ball'));

        $this->assertInstanceOf(Meta::class, $meta->ofProperty('no'));
        $this->assertInstanceOf(Meta::class, $meta->ofProperty('nop'));
        
        $this->assertSame('123', $meta->ofProperty('x')['test']);
    }
    
    /**
     * Test $this->createMeta() for a property
     */
    public function testFromAnnotationsForProperty()
    {
        $metaX = $this->createMeta(new \ReflectionProperty(FooBar::class, 'x'));
        $this->assertInstanceOf(Meta::class, $metaX);
        $this->assertSame('float', $metaX['var']);
        $this->assertSame('123', $metaX['test']);
        $this->assertSame(true, $metaX['required']);
        
        $metaY = $this->createMeta(new \ReflectionProperty(FooBar::class, 'y'));
        $this->assertInstanceOf(Meta::class, $metaY);
        $this->assertSame('int', $metaY['var']);
    }
    
    /**
     * Test Meta::normalizeVar() for a property
     */
    public function testNormalizeVarForProprety()
    {
        $metaBall = $this->createMeta(new \ReflectionProperty(FooBar::class, 'ball'));
        $this->assertInstanceOf(Meta::class, $metaBall);
        $this->assertSame('Ball', $metaBall['var']);
        
        $metaBike = $this->createMeta(new \ReflectionProperty(FooBar::class, 'bike'));
        $this->assertInstanceOf(Meta::class, $metaBike);
        $this->assertSame('Jasny\Meta\Test\Bike', $metaBike['var']);
    }

    /**
     * Test Meta::normalizeVar() for a property
     */
    public function testAccessForProprety()
    {
        $metaBall = $this->createMeta(new \ReflectionProperty(FooBar::class, 'ball'));
        $this->assertInstanceOf(Meta::class, $metaBall);
        $this->assertSame('public', $metaBall['access']);
        
        $metaNo = $this->createMeta(new \ReflectionProperty(FooBar::class, 'no'));
        $this->assertInstanceOf(Meta::class, $metaNo);
        $this->assertSame('protected', $metaNo['access']);
        
        $metaBike = $this->createMeta(new \ReflectionProperty(FooBar::class, 'bike'));
        $this->assertInstanceOf(Meta::class, $metaBike);
        $this->assertSame('private', $metaBike['access']);
    }
    
    /**
     * Test Meta::normalizeVar() for a metohd
     */
    public function testNormalizeVarForMethod()
    {
        $meta = $this->createMeta(new \ReflectionMethod(FooBar::class, 'read'));
        $this->assertInstanceOf(Meta::class, $meta);
        $this->assertSame('Jasny\Meta\Test\Book', $meta['return']);
    }
    
    
    /**
     * Test Meta::get()
     * 
     * @depends testFromAnnotationsForClass
     */
    public function testGet()
    {
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        
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
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        
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
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        
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
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        $props = $meta->ofProperties();
        
        $this->assertArrayHasKey('x', $props);
        $this->assertArrayHasKey('y', $props);
        $this->assertArrayHasKey('no', $props);
        $this->assertArrayHasKey('ball', $props);
        $this->assertArrayHasKey('bike', $props);
        
        $this->assertInstanceOf(Meta::class, $props['x']);
        $this->assertInstanceOf(Meta::class, $props['y']);
        $this->assertInstanceOf(Meta::class, $props['no']);
        $this->assertInstanceOf(Meta::class, $props['ball']);
        $this->assertInstanceOf(Meta::class, $props['bike']);
        
        $this->assertArrayNotHasKey('nop', $props);
        
        $this->assertSame($meta->ofProperty('x'), $props['x']);
        $this->assertSame($meta->ofProperty('y'), $props['y']);
    }
    
    
    public function testClone()
    {
        $meta = $this->createMeta(new \ReflectionClass(FooBar::class));
        
        $clone = clone $meta;
        
        $this->assertEquals($meta->ofProperty('x'), $clone->ofProperty('x'));
        $this->assertNotSame($meta->ofProperty('x'), $clone->ofProperty('x'));
    }
    
    
    public function testDefaultCache()
    {
        $this->assertInstanceOf(Cache\Simple::class, Meta::cache());
    }
    
    public function testUseCache()
    {
        $cache = new Cache\Simple();
        Meta::useCache($cache);
        
        $this->assertSame($cache, Meta::cache());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testUseCacheInvalidArgument()
    {
        Meta::useCache(new \stdClass());
    }
}
