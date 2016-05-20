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

The `Jasny\Meta\Introspection\AnnotationsImplementation` [trait](http://php.net/manual/en/language.oop5.traits.php) adds a static method `meta()` to your class, which returns the parsed annotations as metadata.

Implement the `Jasny\Meta\Introspection` [interface](http://php.net/manual/en/language.oop5.interfaces.php) to indicate
that the class has accessable metadata through the `meta()` method.

```php
use Jasny\Meta\Introspection;

/**
 * A system user
 *
 * @represents A person or organization
 * @softdelete
 */
class User implements Jasny\Meta\Introspection
{
    use Introspection\AnnotationsImplementation;

    /**
     * The user's e-mail address.
     *
     * @var string
     * @type url
     * @required
     */
    public $website;
    
    ..
}

echo User::meta()['represents'];                  // A person or organization
echo User::meta()['softdelete'] ? 'yes' : 'no';   // yes
echo User::meta()['lazy'] ? 'yes' : 'no';         // no

echo User::meta()->ofProperty('website')['var'];  // string
```


Meta class
---

The `Jasny\Meta` class extends [ArrayObject](http://php.net/manual/en/class.arrayobject.php). The metadata is
accessable as associated array or through the `get()` method.

```php
User::meta()['foo'];
User::meta()->get('foo');
```

Requesting non-existing keys will return `null` and won't trigger a notice.

You may also set or add metadata. Either using the Meta object as associated array or through the `set()` method.

```
User::meta()['foo'] = true;
User::meta()->set($key, $value);
User::meta()->set(array $values);
```


#### Class properties

metadata of class properties are available as properties of the of the Meta object or through the `ofProperty()`
method. To get the meta of all properties use the `ofProperties()` method.

```php
User::meta()->ofProperty('email')['required'];
User::meta()->ofProperty('email')->get('required');
User::meta()->ofProperties(); // Get metadata of all class properties
```


Custom metadata
---

You may wish to add metadata from another source. Instead of using the `Jasny\Meta\Annotations` trait, create your own
implementation of the `meta()` method.

```php
/**
 * A system user
 *
 * @softdelete
 */
class User implements Jasny\Meta\Introspection
{
    /**
     * Get class metadata
     *
     * @return Jasny\Meta
     */
    public static funciton meta()
    {
        return new Jasny\Meta(['abc' => 10, 'def' => 20]);
    }
}
```

Todo
---

* Support multiple annotations with the same key (eg @param)
