<?php

namespace MetaTest;

/**
 * A test class.
 * @ignore
 * 
 * @foo
 * @bar  Hello world
 * @blue 22
 */
class FooBar
{
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
     * Should not be in here
     * @var string
     */
    protected $no;
    
    /**
     * @var \Ball
     */
    public $ball;
    
    /**
     * @var Bike
     */
    public $bike;
    
    /**
     * Just a test
     * 
     * @return Book
     */
    public function read()
    {
    }
}
