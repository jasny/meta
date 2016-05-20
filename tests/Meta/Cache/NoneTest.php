<?php

namespace Jasny\Meta\Cache;

use Jasny\Meta;
use Jasny\Meta\Cache;

/**
 * @covers Jasny\Meta\Cache\None
 */
class NoneTest extends \PHPUnit_Framework_TestCase
{
    protected $cache;
    
    protected function setUp()
    {
        $this->cache = new Cache\None;
    }
    
    public function testCached()
    {
        $meta = new Meta();
        $this->cache->set('abc', $meta);
        
        $this->assertFalse($this->cache->has('abc'));
        $this->assertNull($this->cache->get('abc'));
    }
    
    public function testNotCached()
    {
        $this->assertFalse($this->cache->has('abc'));
        $this->assertNull($this->cache->get('abc'));
    }
}
