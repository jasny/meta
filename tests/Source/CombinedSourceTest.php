<?php

declare(strict_types=1);

namespace Jasny\Meta\Tests\Source;

use Jasny\Meta\Source\SourceInterface;
use Jasny\Meta\Source\CombinedSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers Jasny\Meta\Source\CombinedSource
 */
class CombinedSourceTest extends TestCase
{
    /**
     * Provide data for testing 'forClass' method
     *
     * @return array
     */
    public function forClassProvider()
    {
        $source1 = $this->createMock(SourceInterface::class);
        $source2 = $this->createMock(SourceInterface::class);
        $source3 = $this->createMock(SourceInterface::class);

        $meta1 = [
            'name' => 'Foo',
            'title' => 'Some foo',
            'properties' => [
                'foo' => [
                    'var' => 'int',
                    'required' => true
                ],
                'bar' => [
                    'access' => 'private'
                ]
            ]
        ];

        $meta2 = [
            'package' => 'tesla',
            'title' => 'another title'
        ];

        $meta3 = [
            'author' => 'Jimi Hendrix',
            'properties' => [
                'zet' => [
                    'important' => true,
                    'var' => 'Zet'
                ],
                'foo' => [
                    'name' => 'foo',
                    'var' => 'string'
                ],
                'bar' => [
                    'access' => 'public',
                    'title' => 'some bar'
                ]
            ]
        ];

        $expected = [
            'name' => 'Foo',
            'title' => 'another title',
            'package' => 'tesla',
            'author' => 'Jimi Hendrix',
            'properties' => [
                'foo' => [
                    'var' => 'string',
                    'required' => true,
                    'name' => 'foo',
                ],
                'bar' => [
                    'access' => 'public',
                    'title' => 'some bar'
                ],
                'zet' => [
                    'important' => true,
                    'var' => 'Zet'
                ]
            ]
        ];

        $source1->expects($this->once())->method('forClass')->with('Foo')->willReturn($meta1);
        $source2->expects($this->once())->method('forClass')->with('Foo')->willReturn($meta2);
        $source3->expects($this->once())->method('forClass')->with('Foo')->willReturn($meta3);

        return [
            [[$source1, $source2, $source3], $expected],
            [[], []]
        ];
    }

    /**
     * Test 'forClass' method
     *
     * @dataProvider forClassProvider
     */
    public function testForClass($sources, $expected)
    {
        $source = new CombinedSource($sources);
        $result = $source->forClass('Foo');

        $this->assertEquals($expected, $result);
    }
}
