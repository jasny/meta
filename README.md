Jasny Meta
===

[![Build Status](https://travis-ci.org/jasny/meta.svg?branch=master)](https://travis-ci.org/jasny/meta)
[![Coverage Status](https://coveralls.io/repos/github/jasny/meta/badge.svg?branch=master)](https://coveralls.io/github/jasny/meta?branch=master)

The Jasny Meta library allows you to attach metadata to a class and class properties. The metadata is available at
runtime and can be used to trigger particular behaviour.

Installation
---

The Jasny Meta package is available on [packagist](https://packagist.org/packages/jasny/meta). Install it using
composer:

    composer require jasny/meta


Annotations
---

Metadata can be specified through annotations. In PHP annotations are written in the docblock and start
with `@`. If you're familiar with writing docblocks, you probably recognize them.

Here's an example of obtaining metadata for given class:

```php
use Jasny\Meta\Factory;

$factory = new Factory($source, $cache);
$meta = $factory->forClass(FooBar::class);
```

In here:

* `$source` is an implementation of `Jasny\Meta\Source\SourceInterface` - to obtain meta-data from class and return it as associative array
* `$cache` is an implementation of `Psr\SimpleCache\CacheInterface` - to handle caching meta (interface is defined in [PHP FIG Simple Cache](https://github.com/php-fig/simple-cache))
* `$meta` returned is an instance of `Jasny\Meta\MetaClass`.

Lets look closely at all of those.

Meta Sources
---

Source is an object that actually obtains meta data from class definition. We have three classes of sources defined:

* `Jasny\Meta\Source\PhpdocSource` - for obtaining meta data, defined in doc-comments
* `Jasny\Meta\Source\ReflectionSource` - for obtaining some generic information, using reflection methods
* `Jasny\Meta\Source\CombinedSource` - a class that uses other sources to get meta and to merge it in a single output array

### PhpdocSource

Given a class:

```php
/**
 * Original FooBar class
 *
 * @package FoosAndBars
 * @author Jimi Hendrix <jimi-guitars@example.com>
 */
class FooBar
{
    /**
     * Very first property
     * @var array
     * @required
     */
    public $first;

    /**
     * And some more property
     * @var string|Foo
     * @version 3.4
     * @default 'a_place_for_foo'
     */
    public $secondFoo;

    /**
     * Protected properties are not included in fetched meta
     * @var int
     */
    protected $third;

    /**
     * Privates also are not included
     * @var array
     */
    private $fourth;

    //Methods and constants are not included in meta for now
}
```

We obtain meta-data:

```php
use Jasny\Meta\Source\PhpdocSource;
use Jasny\ReflectionFactory\ReflectionFactory;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;

$reflectionFactory = new ReflectionFactory();

$tags = PhpDocumentor::tags();
$phpdocParser = new PhpdocParser($tags);

$source = new PhpdocSource($reflectionFactory, $phpdocParser);
$meta = $source->forClass(FooBar::class);

var_export($meta);
```

```php
[
    'package' => 'FoosAndBars',
    'author' => [
        'name' => 'Jimi Hendrix',
        'email' => 'jimi-guitars@example.com'
    ],
    '@properties' => [
        'first' => [
            'var' => 'array',
            'required' => true
        ],
        'secondFoo' => [
            'var' => 'string|Foo',
            'version' => '3.4',
            'default' => 'a_place_for_foo'
        ]
    ]
]
```

In here we used two additional dependencies:

* `Jasny\ReflectionFactory\ReflectionFactory` - class for creating reflections, is defined in [Jasny Reflection factory](https://github.com/jasny/reflection-factory)
* `Jasny\PhpdocParser\PhpdocParser` - class for parsing doc-comments, is defined in [Jasny PHPDoc parser](https://github.com/jasny/phpdoc-parser)

### ReflectionSource

This class does not take any information from doc-comments, but fetches data using only reflection methods.

Having a class from previous example as input, we obtain meta-data:

```php
use Jasny\Meta\Source\ReflectionSource;
use Jasny\ReflectionFactory\ReflectionFactory;

$reflectionFactory = new ReflectionFactory();

$source = new ReflectionSource($reflectionFactory);
$meta = $source->forClass(FooBar::class);

var_export($meta);
```

```php
[
    'name' => 'Some\\Namespace\\FoosAndBars',
    'title' => 'foos and bars',
    '@properties' => [
        'first' => [
            'name' => 'first',
            'title' => 'first',
            'default' => null
        ],
        'secondFoo' => [
            'name' => 'secondFoo',
            'title' => 'second foo',
            'default' => 'a_place_for_foo'
        ]
    ]
]
```

`$reflectionFactory` dependency is the same as defined in the upper example for `PhpdocSource`.

### CombinedSource

Here's an example for the same class definition:

```php
$sources = [$phpdocSource, $reflectionSource];
$source = new CombinedSource($sources);
$meta = $source->forClass(FooBar::class);

var_export($meta);
```

```php
[
    'package' => 'FoosAndBars',
    'author' => [
        'name' => 'Jimi Hendrix',
        'email' => 'jimi-guitars@example.com'
    ],
    'name' => 'Some\\Namespace\\FoosAndBars',
    'title' => 'foos and bars',
    '@properties' => [
        'first' => [
            'var' => 'array',
            'required' => true,
            'name' => 'first',
            'title' => 'first',
            'default' => null
        ],
        'secondFoo' => [
            'var' => 'string|Foo',
            'version' => '3.4',
            'name' => 'secondFoo',
            'title' => 'second foo',
            'default' => 'a_place_for_foo'
        ]
    ]
]
```

As you see, meta, obtained by means of `$phpdocSource` and `$reflectionSource`, was merged into a single array.

Caching
---

The second parameter to pass to factory constructor is an instance of `Psr\SimpleCache\CacheInterface`. It is used to cache meta-data between calls for the same class name.

We have two implementations of cache:

* `Jasny\Meta\Cache\None` - actually does not perform any caching, used to simplify a code for cache usage
* `Jasny\Meta\Cache\Simple` - caching into a process memory (so in array). This cache does not persist among different php processes and lasts till the current process ends.

So if you don't want to cache meta, just use a `$cache = new Jasny\Meta\Cache\None()`.

Meta
---

Meta returned by factory is an instance of `Jasny\Meta\MetaClass`. It has the following methods to obtain data:

* `get(string $key, $default = null)` - get class meta by key
* `is(string $key): bool` - check if meta key exists and is not empty
* `has(string $key): bool` - check if meta key exists (can be empty)
* `getProperty(string $name): ?MetaProperty` - obtain meta data for given class property. Result is either null, if property does not exists, or an instance of `Jasny\Meta\MetaProperty`
* `getProperties(): array` - get meta data for all class properties as array of `Jasny\Meta\MetaProperty` objects

`MetaProperty` class implements the first three methods of those (so `get`, `is` and `has`).
