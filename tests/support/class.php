<?php

namespace Jasny\Meta\Test;

use Jasny\Meta\Introspection;

/**
 * A test class.
 * @ignore
 * 
 * @foo
 * @bar  Hello world
 * @blue 22
 */
class FooBar implements Introspection
{
    use Introspection\AnnotationsImplementation;
    
    /**
     * The X is here
     * 
     * @var float
     * @test 123
     * @required
     */
    public $x;
    
    /** @var int */
    public $y;
    
    /**
     * @var string
     */
    protected $no;
    
    /**
     * @var \Ball
     */
    public $ball;
    
    /**
     * @var Bike
     * @access private
     */
    public $bike;
    
    /**
     * Class constructor
     * 
     * @param mixed $x
     */
    public function __construct($x = null)
    {
        $this->x = $x;
    }
    
    /**
     * Just a test
     * 
     * @return Book
     */
    public function read()
    {
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return 'foo';
    }
}
