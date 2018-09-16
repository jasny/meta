<?php

declare(strict_types=1);

namespace Jasny\Meta\Tests;

use Jasny\Meta\Source\ReflectionSource;
use Jasny\ReflectionFactory\ReflectionFactory;
use PHPUnit\Framework\TestCase;
use function Jasny\uncase;
use ReflectionClass;

/**
 * @covers Jasny\Meta\Source\ReflectionSource
 */
class ReflectionSourceTest extends TestCase
{
    /**
     * Provide data for testing 'forClass' method
     *
     * @return array
     */
    public function forClassProvider()
    {
        $object1 = static::getObject();
        $object2 = static::getObjectNoPublicProperties();

        $class1 = get_class($object1);
        $class2 = get_class($object2);

        $shortName1 = substr($class1, strrpos($class1, '\\') + 1);
        $shortName2 = substr($class2, strrpos($class2, '\\') + 1);

        return [
            [
                $class1,
                [
                    'name' => $class1,
                    'title' => uncase($shortName1),
                    '@properties' => [
                        'foo' => [
                            'name' => 'foo',
                            'title' => 'foo',
                            'default' => 'some_foo'
                        ],
                        'barGar' => [
                            'name' => 'barGar',
                            'title' => 'bar gar',
                            'default' => null
                        ],
                        'zoo_vox' => [
                            'name' => 'zoo_vox',
                            'title' => 'zoo vox',
                            'default' => 'any_zoo'
                        ],
                        'boo' => [
                            'name' => 'boo',
                            'title' => 'boo',
                            'default' => null
                        ],
                    ]
                ]
            ],
            [
                $class2,
                [
                    'name' => $class2,
                    'title' => uncase($shortName2),
                    '@properties' => []
                ]
            ],
        ];
    }

    /**
     * Test 'forClass' method
     *
     * @dataProvider forClassProvider
     */
    public function testForClass($class, $expected)
    {
        $reflection = new ReflectionClass($class);

        $reflectionFactory = $this->createMock(ReflectionFactory::class);
        $reflectionFactory->expects($this->once())->method('reflectClass')->with($class)->willReturn($reflection);

        $source = new ReflectionSource($reflectionFactory);
        $result = $source->forClass($class);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'forClass' method, if class does not exist
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot get meta for non-exist class 'Foo'
     */
    public function testForClassNotExist()
    {
        $reflectionFactory = $this->createMock(ReflectionFactory::class);

        $source = new ReflectionSource($reflectionFactory);
        $result = $source->forClass('Foo');
    }

    /**
     * Get object to get meta for
     *
     * @return object
     */
    protected static function getObject()
    {
        return new class() {
            public static $foo = 'some_foo';
            public static $barGar;
            public $zoo_vox = 'any_zoo';
            public $boo;
            protected $wha;
            private $bla;
        };
    }

    /**
     * Get object to get meta for
     *
     * @return object
     */
    protected static function getObjectNoPublicProperties()
    {
        return new class() {
            protected $wha = 'some_default';
            private $bla;
        };
    }
}
