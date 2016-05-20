<?php

namespace Jasny\Meta\Cache;

use Jasny\Meta;
use Jasny\Meta\Cache;

/**
 * @covers Jasny\Meta\Cache\Simple
 */
class SimpleTest extends \PHPUnit_Framework_TestCase
{
    protected $cache;
    
    protected function setUp()
    {
        $this->cache = new Cache\Simple;
    }
    
    public function testCached()
    {
        $meta = new Meta();
        $meta->set('a', 1);
        $meta->ofProperty('b')->set('c', 2);
        
        $this->cache->set('abc', $meta);
        
        $this->assertTrue($this->cache->has('abc'));
        
        $this->assertEquals($meta, $this->cache->get('abc'));
        $this->assertNotSame($meta, $this->cache->get('abc'));
    }
    
    public function testNotCached()
    {
        $this->assertFalse($this->cache->has('abc'));
        $this->assertNull($this->cache->get('abc'));
    }
}
