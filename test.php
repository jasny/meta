<?php

/**
 * Test class
 * 
 * @options("abc", "def", "hij", "klm")
 * @Foo
 * @Foo1("some")
 * @Foo2 some other strings
 * @Foo3(some_label="something here")
 * @Foo4({some: "array here", arr:[1,2,3]})
 * @Foo5(some_label={some: "array here", arr:[1,2,3]})
 * 
 * @filter one
 * @filter two
 * @filter three
 */
class Foo
{
    /**
     * Just an A
     * 
     * @var string
     * @required
     */
    public $a;
    
    /**
     * Hi hello
     * 
     * @return int
     */
    public function helloWorld()
    {
        return 10;
    }
}

require_once './vendor/autoload.php';

$refl = new Notoj\ReflectionClass('Foo');
$annotations = $refl->getAnnotations();

//foreach ($annotations->getAll() as $item) var_dump($item);

var_dump($annotations->getOne('Foo1'));
var_dump($annotations->getOne('Foo2'));
