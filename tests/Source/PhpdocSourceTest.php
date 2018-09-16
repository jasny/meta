<?php

declare(strict_types=1);

namespace Jasny\Meta\Tests;

use Jasny\Meta\Source\PhpdocSource;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\ReflectionFactory\ReflectionFactory;
use PHPUnit\Framework\TestCase;
use function Jasny\uncase;
use ReflectionClass;
use ReflectionProperty;

/**
 * @covers Jasny\Meta\Source\PhpdocSource
 */
class PhpdocSourceTest extends TestCase
{
    /**
     * Test 'forClass' method
     */
    public function testForClass()
    {
        $class = get_class(new class(){});
        $classDoc = <<<DOC
/**
 * Some class comment
 *
 * @package Wonders
 */
DOC;

        $propertyDoc1 = <<<DOC
/**
 * @var array
 * @required
 */
DOC;

        $propertyDoc2 = <<<DOC
/**
 * @var int
 * @important
 */
DOC;

        $property1 = $this->createMock(ReflectionProperty::class);
        $property2 = $this->createMock(ReflectionProperty::class);

        $property1->expects($this->once())->method('getDocComment')->willReturn($propertyDoc1);
        $property2->expects($this->once())->method('getDocComment')->willReturn($propertyDoc2);
        $property1->expects($this->once())->method('getName')->willReturn('foo_var');
        $property2->expects($this->once())->method('getName')->willReturn('bar_var');

        $properties = [$property1, $property2];

        $reflection = $this->createMock(ReflectionClass::class);
        $reflection->expects($this->once())->method('getDocComment')->willReturn($classDoc);
        $reflection->expects($this->once())->method('getProperties')->with(ReflectionProperty::IS_PUBLIC)->willReturn($properties);

        $reflectionFactory = $this->createMock(ReflectionFactory::class);
        $reflectionFactory->expects($this->once())->method('reflectClass')->with($class)->willReturn($reflection);

        $parser = $this->createMock(PhpdocParser::class);
        $parser->expects($this->at(0))->method('parse')->with($classDoc)->willReturn([
            'summary' => 'Some class comment',
            'description' => 'Some class comment',
            'package' => 'Wonders'
        ]);
        $parser->expects($this->at(1))->method('parse')->with($propertyDoc1)->willReturn([
            'var' => 'array',
            'required' => true
        ]);
        $parser->expects($this->at(2))->method('parse')->with($propertyDoc2)->willReturn([
            'var' => 'int',
            'important' => true
        ]);

        $source = new PhpdocSource($reflectionFactory, $parser);
        $result = $source->forClass($class);

        $expected = [
            'summary' => 'Some class comment',
            'description' => 'Some class comment',
            'package' => 'Wonders',
            '@properties' => [
                'foo_var' => [
                    'var' => 'array',
                    'required' => true
                ],
                'bar_var' => [
                    'var' => 'int',
                    'important' => true
                ]
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
        $parser = $this->createMock(PhpdocParser::class);

        $source = new PhpdocSource($reflectionFactory, $parser);
        $result = $source->forClass('Foo');
    }
}
