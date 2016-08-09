<?php

namespace Jasny\Meta;

use Jasny\Meta\Test\FooBar;

/**
 * Tests for Jasny\Meta\TypeCasting.
 * 
 * @package Test
 * @author Arnold Daniels
 */
class TypeCastingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Clean up after executing
     */
    public function tearDown()
    {
        TypeCastingObject::setType(null);
        parent::tearDown();
    }
    
    /**
     * Test type casting to a string
     */
    public function testCastValueToString()
    {
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame('100', $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame('', $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame('1', $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame('1', $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame('', $obj->prop);
    }
    
    /**
     * Test type casting an object with `__toString` to a string
     */
    public function testCastValueToStringWithStringable()
    {
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = new FooBar();  // Implement __toString
        $obj->cast();
        $this->assertSame('foo', $obj->prop);
    }
    
    /**
     * Test type casting an DateTime to a string
     */
    public function testCastValueToStringWithDateTime()
    {
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = new \DateTime("2014-12-31 23:15 UTC");
        $obj->cast();
        $this->assertSame('2014-12-31T23:15:00+00:00', $obj->prop);
    }
    
    /**
     * Test type casting an array to a string
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from an array to a string
     */
    public function testCastValueToStringWithArray()
    {
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a string
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a stdClass object to a string
     */
    public function testCastValueToStringWithObject()
    {
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a string
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to a string
     */
    public function testCastValueToStringWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('string');
        
        $obj = new TypeCastingObject();

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    
    /**
     * Test type casting for boolean
     */
    public function testCastValueToBoolean()
    {
        TypeCastingObject::setType('boolean');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = true;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame(false, $obj->prop);

        foreach ([1, -1, 10, '1', 'true', 'yes', 'on'] as $value) {
            $obj->prop = $value;
            $obj->cast();
            $this->assertSame(true, $obj->prop, $value);
        }

        foreach ([0, '', '0', 'false', 'no', 'off'] as $value) {
            $obj->prop = $value;
            $obj->cast();
            $this->assertSame(false, $obj->prop, $value);
        }
    }
    
    /**
     * Test type casting an array to a boolean
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from an array to a boolean
     */
    public function testCastValueToBooleanWithArray()
    {
        TypeCastingObject::setType('boolean');
        
        $obj = new TypeCastingObject();

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a boolean
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a stdClass object to a boolean
     */
    public function testCastValueToBooleanWithObject()
    {
        TypeCastingObject::setType('boolean');
        
        $obj = new TypeCastingObject();

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a boolean
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to a boolean
     */
    public function testCastValueToBooleanWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('boolean');
        
        $obj = new TypeCastingObject();

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    /**
     * Test type casting for bool (alias for boolean)
     */
    public function testCastValueToBool()
    {
        TypeCastingObject::setType('bool');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame(false, $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
    }
    
    
    /**
     * Test type casting for integer
     */
    public function testCastValueToInteger()
    {
        TypeCastingObject::setType('integer');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = 0;
        $obj->cast();
        $this->assertSame(0, $obj->prop);
        
        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10, $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100, $obj->prop);
        
        $obj->prop = '-100.4';
        $obj->cast();
        $this->assertSame(-100, $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame(0, $obj->prop);
    }
    
    /**
     * Test type casting an array to a integer
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from an array to a integer
     */
    public function testCastValueToIntegerWithArray()
    {
        TypeCastingObject::setType('integer');
        
        $obj = new TypeCastingObject();

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a integer
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a stdClass object to a integer
     */
    public function testCastValueToIntegerWithObject()
    {
        TypeCastingObject::setType('integer');
        
        $obj = new TypeCastingObject();

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a integer
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to a integer
     */
    public function testCastValueToIntegerWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('integer');
        
        $obj = new TypeCastingObject();

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    /**
     * Test type casting for int (alias of integer)
     */
    public function testCastValueToInt()
    {
        TypeCastingObject::setType('int');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100, $obj->prop);
    }
    
    
    /**
     * Test type casting for float
     */
    public function testCastValueToFloat()
    {
        TypeCastingObject::setType('float');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10.44, $obj->prop);

        $obj->prop = INF;
        $obj->cast();
        $this->assertSame(INF, $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1.0, $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(1.0, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100.0, $obj->prop);
        
        $obj->prop = '10.44';
        $obj->cast();
        $this->assertSame(10.44, $obj->prop);
        
        $obj->prop = '-10.44';
        $obj->cast();
        $this->assertSame(-10.44, $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame(0.0, $obj->prop);
    }
    
    /**
     * Test type casting an array to a float
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from an array to a float
     */
    public function testCastValueToFloatWithArray()
    {
        TypeCastingObject::setType('float');
        
        $obj = new TypeCastingObject();

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a float
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a stdClass object to a float
     */
    public function testCastValueToFloatWithObject()
    {
        TypeCastingObject::setType('float');
        
        $obj = new TypeCastingObject();

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a float
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to a float
     */
    public function testCastValueToFloatWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('float');
        
        $obj = new TypeCastingObject();

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    
    /**
     * Test type casting for array
     */
    public function testCastValueToArray()
    {
        TypeCastingObject::setType('array');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertSame([1, 20, 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = 20;
        $obj->cast();
        $this->assertSame([20], $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame([false], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = 'foo';
        $obj->cast();
        $this->assertSame(['foo'], $obj->prop);
        
        $obj->prop = '100, 30, 40';
        $obj->cast();
        $this->assertSame(['100, 30, 40'], $obj->prop);
    }
    
    /**
     * Test type casting an resource to a array
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to an array
     */
    public function testCastValueToArrayWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('array');
        
        $obj = new TypeCastingObject();

        $resource = imagecreate(10, 10);

        $obj->prop = $resource;
        $obj->cast();
    }

    
    /**
     * Test type casting for object
     */
    public function testCastValueToObject()
    {
        TypeCastingObject::setType('object');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['0' => 1, '1' => 20, '2' => 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
    }
    
    /**
     * Test the notice when type casting a scalar value to an object
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from string "foo" to an object
     */
    public function testCastValueToObjectWithScalar()
    {
        TypeCastingObject::setType('object');
        
        $obj = new TypeCastingObject();

        $obj->prop = 'foo';
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a object
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a gd resource to an object
     */
    public function testCastValueToObjectWithResource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingObject::setType('object');
        
        $obj = new TypeCastingObject();

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }

    
    /**
     * Test type casting for DateTime
     */
    public function testDateTime()
    {
        TypeCastingObject::setType('DateTime');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = '2014-06-01T01:15:00+00:00';
        $obj->cast();
        $this->assertInstanceOf('DateTime', $obj->prop);
        $this->assertSame('2014-06-01T01:15:00+00:00', $obj->prop->format('c'));
    }
    
    /**
     * Test type casting for custom class
     */
    public function testClass()
    {
        TypeCastingObject::setType(FooBar::class);
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = 22;
        $obj->cast();
        $this->assertInstanceOf(FooBar::class, $obj->prop);
        $this->assertSame(22, $obj->prop->x);
    }
    
    /**
     * Test the exception when type casting for custom class
     * 
     * @expectedException PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Unable to cast Jasny\Meta\TypeCastingObject::prop from a integer to a MetaTest\NotExistent object: Class doesn't exist
     */
    public function testClassException()
    {
        TypeCastingObject::setType('MetaTest\NotExistent');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = 22;
        $obj->cast();
    }
    
    /**
     * Test type casting for typed array
     */
    public function testTypedArrayOfInt()
    {
        TypeCastingObject::setType('int[]');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [];
        $obj->cast();
        $this->assertSame([], $obj->prop);

        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertSame([1, 20, 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = ['1', '20.3', '-300'];
        $obj->cast();
        $this->assertSame([1, 20, -300], $obj->prop);

        $obj->prop = 20;
        $obj->cast();
        $this->assertSame([20], $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame([0], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
    }
    
    /**
     * Test type casting for typed array with a class
     */
    public function testTypedArrayOfClass()
    {
        TypeCastingObject::setType(FooBar::class . '[]');
        
        $obj = new TypeCastingObject();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [];
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(3, $obj->prop);
        foreach ([1, 20, 300] as $key => $value) {
            $this->assertArrayHasKey($key, $obj->prop);
            $this->assertInstanceOf(FooBar::class, $obj->prop[$key]);
            $this->assertSame($value, $obj->prop[$key]->x);
        }

        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(3, $obj->prop);
        foreach (['red' => 1, 'green' => 20, 'blue' => 300] as $key => $value) {
            $this->assertArrayHasKey($key, $obj->prop);
            $this->assertInstanceOf(FooBar::class, $obj->prop[$key]);
            $this->assertSame($value, $obj->prop[$key]->x);
        }
        
        $obj->prop = 20;
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(1, $obj->prop);
        $this->assertArrayHasKey(0, $obj->prop);
        $this->assertInstanceOf(FooBar::class, $obj->prop[0]);
        $this->assertSame(20, $obj->prop[0]->x);
    }
}
