<?php

namespace Jasny\Meta\Tests\Cache;

use Jasny\Meta\MetaClass;
use Jasny\Meta\Cache;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\Cache\None
 */
class NoneTest extends TestCase
{
    protected $cache;

    protected function setUp()
    {
        $this->cache = new Cache\None();
    }

    public function testCached()
    {
        $meta = $this->createMock(MetaClass::class);

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
