<?php

namespace Jasny\Meta\Tests\Cache;

use Jasny\Meta\MetaClass;
use Jasny\Meta\Cache;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\Cache\Simple
 */
class SimpleTest extends TestCase
{
    protected $cache;

    protected function setUp()
    {
        $this->cache = new Cache\Simple();
    }

    public function testCached()
    {
        $meta = $this->createMock(MetaClass::class);

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
