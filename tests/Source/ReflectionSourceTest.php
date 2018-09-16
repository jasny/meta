<?php

declare(strict_types=1);

namespace Jasny\Meta\Tests;

use Jasny\Meta\Source\ReflectionSource;
use Jasny\ReflectionFactory\ReflectionFactory;
use Jasny\Meta\Tests\Support\ClassWithPublicProperties;
use Jasny\Meta\Tests\Support\ClassWithoutPublicProperties;
use PHPUnit\Framework\TestCase;
use function Jasny\uncase;
use ReflectionClass;
use ReflectionProperty;

/**
 * @covers Jasny\Meta\Source\ReflectionSource
 */
class ReflectionSourceTest extends TestCase
{
    /**
     * Test 'forClass' method
     */
    public function testForClass()
    {
        $class = ReflectionSourceTest::class;
        $shortName = 'ReflectionSourceTest';
        $title = 'reflection source test';

        $reflection = $this->createMock(ReflectionClass::class);
        $property1 = $this->createMock(ReflectionProperty::class);
        $property2 = $this->createMock(ReflectionProperty::class);
        $properties = [$property1, $property2];

        $property1->expects($this->once())->method('getName')->willReturn('fooBar');
        $property2->expects($this->once())->method('getName')->willReturn('bar_zoo');

        $reflection->expects($this->once())->method('getName')->willReturn($class);
        $reflection->expects($this->once())->method('getShortName')->willReturn($shortName);
        $reflection->expects($this->once())->method('getProperties')->with(ReflectionProperty::IS_PUBLIC)->willReturn($properties);
        $reflection->expects($this->once())->method('getDefaultProperties')->willReturn(['fooBar' => 'test_default', 'bar_zoo' => null]);

        $reflectionFactory = $this->createMock(ReflectionFactory::class);
        $reflectionFactory->expects($this->once())->method('reflectClass')->with($class)->willReturn($reflection);

        $source = new ReflectionSource($reflectionFactory);
        $result = $source->forClass($class);

        $expected = [
            'name' => $class,
            'title' => $title,
            '@properties' => [
                'fooBar' => [
                    'name' => 'fooBar',
                    'title' => 'foo bar',
                    'default' => 'test_default'
                ],
                'bar_zoo' => [
                    'name' => 'bar_zoo',
                    'title' => 'bar zoo',
                    'default' => null
                ],
            ]
        ];

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
}
