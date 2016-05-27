<?php

namespace Jasny\Meta\Factory;

use Jasny\Meta;
use Jasny\Meta\Factory;
use Jasny\Meta\Cache;

use Reflector;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use InvalidArgumentException;

/**
 * Factory to create Meta from annotations
 */
class Annotations implements Factory
{
    /**
     * Meta cache
     * @var Cache
     */
    protected static $cache;
    
    /**
     * Create Meta object from doc comment
     * 
     * @param string $docComment
     * @return Meta
     */
    protected function createFromDocComment($docComment)
    {
        $data = $this->parseDocComment($docComment);
        return new Meta($data);
    }
    
    /**
     * Get metadata from annotations
     *
     * @param ReflectionClass|ReflectionProperty|ReflectionMethod $refl
     * @return Meta
     */
    public function create(Reflector $refl)
    {
        return $this->getFromCache($refl) ?: $this->createFromReflection($refl);
    }
    
    /**
     * Get metadata from cache
     * 
     * @param ReflectionClass|ReflectionProperty|ReflectionMethod $refl
     * @return Meta
     */
    protected function getFromCache(Reflector $refl)
    {
        return $refl instanceof ReflectionClass
            ? self::cache()->get("MetaFromAnnotations:" . $refl->getName())
            : null;
    }
    
    /**
     * Get metadata from reflector
     *
     * @param ReflectionClass|ReflectionProperty|ReflectionMethod $refl
     * @return Meta
     */
    protected function createFromReflection(Reflector $refl)
    {
        if ($refl instanceof ReflectionClass) {
            $meta = $this->createForClass($refl);
        } elseif ($refl instanceof ReflectionProperty) {
            $meta = $this->createForProperty($refl);
        } elseif ($refl instanceof ReflectionMethod) {
            $meta = $this->createForMethod($refl);
        } else {
            throw new \InvalidArgumentException("Unsupported Reflector class: " . get_class($refl));
        }
        
        if ($refl instanceof ReflectionClass) {
            self::cache()->set("MetaFromAnnotations:" . $refl->getName(), $meta);
        }
        
        return $meta;
    }
    
    
    /**
     * Get metadata for a class
     * 
     * @param ReflectionClass $refl
     * @return Meta
     */
    protected function createForClass(ReflectionClass $refl)
    {
        $meta = $this->createFromDocComment($refl->getDocComment());
        $this->addPropertyAnnotations($meta, $refl);
        
        return $meta;
    }
    
    /**
     * Get metadata for a property
     * 
     * @param ReflectionProperty $refl
     * @return Meta
     */
    protected function createForProperty(ReflectionProperty $refl)
    {
        $meta = $this->createFromDocComment($refl->getDocComment());
        
        $meta['access'] = $meta['access'] ?: $this->determineAccess($refl);
        
        if (isset($meta['var'])) {
            $meta['var'] = $this->normalizeVar($refl, $meta['var']);
        }
        
        return $meta;
    }
    
    /**
     * Get metadata for a method
     * 
     * @param ReflectionMethod $refl
     * @return Meta
     */
    protected function createForMethod(ReflectionMethod $refl)
    {
        $meta = $this->createFromDocComment($refl->getDocComment());
        
        $meta['access'] = $meta['access'] ?: $this->determineAccess($refl);
        
        if (isset($meta['return'])) {
            $meta['return'] = $this->normalizeVar($refl, $meta['return']);
        }
        
        return $meta;
    }
    
    
    /**
     * Add metadata for properties of a class
     *
     * @param Meta             $meta
     * @param ReflectionClass $refl
     */
    protected function addPropertyAnnotations(Meta $meta, ReflectionClass $refl)
    {
        $props = $refl->getProperties();

        foreach ($props as $prop) {
            $propertyMeta = $this->create($prop);
            $meta->ofProperty($prop->getName())->set($propertyMeta);
        }
    }
    
    /**
     * Parse a docblock and extract annotations
     *
     * @param string $doc
     * @return array
     */
    protected function parseDocComment($doc)
    {
        $ann = [];
        $matches = null;

        $regex = '/^\s*(?:\/\*)?\*\s*@(\S+)(?:\h+(\S.*?)|\h*)(?:\*\*\/)?\r?$/m';
        
        if (preg_match_all($regex, $doc, $matches, PREG_PATTERN_ORDER)) {
            $keys = $matches[1];
            $values = array_map(function ($v) {
                return trim($v) === '' ? true : trim($v);

            }, $matches[2]);
            $ann += array_combine($keys, $values);
        }
        
        return $ann;
    }
    
    /**
     * Clean/Normalize var annotation gotten through reflection
     *
     * @param ReflectionProperty|ReflectionMethod $refl
     * @param string                              $var
     * @return string
     */
    protected function normalizeVar(Reflector $refl, $var)
    {
        if (!$refl instanceof ReflectionProperty && !$refl instanceof ReflectionMethod) {
            throw new InvalidArgumentException("Unsupported Reflector class: " . get_class($refl));
        }
        
        if (strstr($var, '|')) {
            $vars = explode('|', $var);
            return join('|', array_map(function ($subvar) use ($refl) {
                return $this->normalizeVar($refl, $subvar);
            }, $vars));
        }
        
        // Remove additional var info
        if (strpos($var, ' ') !== false) $var = substr($var, 0, strpos($var, ' '));

        // Normalize call types to global namespace
        $internalTypes = ['bool', 'boolean', 'int', 'integer', 'float', 'string', 'array', 'object', 'resource',
            'mixed', 'self', 'static', '$this'];
        
        if (!isset($var) || in_array($var, $internalTypes)) {
            return $var;
        }
        
        if ($var[0] === '\\') {
            $var = substr($var, 1);
        } else {
            $ns = $refl->getDeclaringClass()->getNamespaceName();
            if ($ns) $var = $ns . '\\' . $var;
        }
        
        return $var;
    }
    
    /**
     * Determine property/method access
     * 
     * @param ReflectionProperty|ReflectionMethod $refl
     * @return string  'private', 'protected', 'public'
     */
    protected function determineAccess(Reflector $refl)
    {
        if (!$refl instanceof ReflectionProperty && !$refl instanceof ReflectionMethod) {
            throw new InvalidArgumentException("Unsupported Reflector class: " . get_class($refl));
        }
        
        return $refl->isPrivate() ? 'private' : ($refl->isProtected() ? 'protected' : 'public');
    }
    
    
    /**
     * Get the cache interface
     * 
     * @return Cache|\Desarrolla2\Cache\Cache
     */
    final public static function cache()
    {
        if (!isset(static::$cache)) {
            static::useCache(new Cache\Simple());
        }
        
        return static::$cache;
    }
    
    /**
     * Set cache interface
     * 
     * @param Cache|\Desarrolla2\Cache\Cache $cache
     */
    final public static function useCache($cache)
    {
        if (!$cache instanceof Cache && !$cache instanceof \Desarrolla2\Cache\Cache) {
            throw new \InvalidArgumentException("Cache should be Jasny\Meta\Cache or Desarrolla2\Cache\Cache");
        }
        
        static::$cache = $cache;
    }
}
