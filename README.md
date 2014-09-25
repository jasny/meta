Jasny Meta
===

![Travis CI](https://api.travis-ci.org/jasny/meta.svg)

The Jasny Meta library allows you to attach metadata to a class and class properties. The metadata is available at
runtime and can be used to trigger particular behaviour.

Installation
---

The Jasny Meta package is available on [packagist](https://packagist.org/packages/jasny/meta). Install it using
composer:

    composer require jasny/meta


Annotations
---

The default way of specifying metadata is through annotations. In PHP annotations are written in the docblock and start
with `@`. If you're familiar with writing docblocks, you probably recognize them.

```php
/**
 * A system user
 *
 * @softdelete
 */
class User
{
    /**
     * The user's e-mail address.
     *
     * @var string
     * @type url
     * @required
     */
    public $website;
}

$refl = new ReflectionClass('User');
$meta = Jasny\Meta($refl);
echo $meta->email['type'];  // url
```

#### Introspection

The `Jasny\Meta\Annotations` [trait](http://php.net/manual/en/language.oop5.traits.php) adds a static method `meta()` to
your class, which returns the parsed annotations as metadata.

Implement the `Jasny\Meta\Introspection` [interface](http://php.net/manual/en/language.oop5.interfaces.php) to indicate
that the class has accessable metadata through the `meta()` method.

```php
/**
 * A system user
 *
 * @softdelete
 */
class User implements Jasny\Meta\Introspection
{
    use Jasny\Meta\Annotations;

    ...
}

echo User::meta()->email['type'];  // url
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

metadata of class properties are available as properties of the of the Meta object or through the `getProperty()`
method.

```php
User::meta()->email['required']
User::meta()->email->get('required')
User::meta()->ofProperties() // Get metadata of all class properties
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
        $refl = new \ReflectionClass(get_called_class());
        $meta = Jasny\Meta::fromAnnotations($refl);
        
        $meta->set(['abc' => 10, 'def' => 20]);
        
        return $meta;
    }
}
```

If you don't want to use annotations at all simple create a new `Jasny\Meta` class.

```php
class User implements Jasny\Meta\Introspection
{
    public static funciton meta()
    {
        return new Jasny\Meta(['abc' => 10, 'def' => 20]);
    }
}
```

Todo
---

* Support complex structures through alternative formats
```php
/** @Foo */
/** @Foo("some") */
/** @Foo some other strings */
/** @Foo(some_label="something here") */
/** @Foo({some: "array here", arr:[1,2,3]}) */
/** @Foo(some_label={some: "array here", arr:[1,2,3]}) */
```
* Add caching
* Support multiple annotations with the same key (eg @param)
