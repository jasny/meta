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
     * Caching
     * @var Cache|\Desarrolla2\Cache\Cache
     */
    protected $cache;
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->cache = new Cache\Simple();
    }
    
    /**
     * Set cache interface
     * 
     * @param Cache|\Desarrolla2\Cache\Cache $cache
     */
    public function useCache($cache)
    {
        if (!$cache instanceof Cache && !$cache instanceof \Desarrolla2\Cache\Cache) {
            throw new \InvalidArgumentException("Cache should be Jasny\DB\Meta\Cache or Desarrolla2\Cache\Cache");
        }
        
        $this->cache = $cache;
    }
    
    /**
     * Cache meta
     * 
     * @param Reflector $refl
     * @param Meta      $meta
     */
    public function cache(Reflector $refl, Meta $meta)
    {
        if (!$refl instanceof ReflectionClass) return;
        
        $this->cache->set($refl->getName() . '::meta', $meta);
    }
    
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
        if ($refl instanceof ReflectionClass) {
            $meta = $this->createForClass($refl);
        } elseif ($refl instanceof ReflectionProperty) {
            $meta = $this->createForProperty($refl);
        } elseif ($refl instanceof ReflectionMethod) {
            $meta = $this->createForMethod($refl);
        } else {
            throw new \InvalidArgumentException("Unsupported Reflector class: " . get_class($refl));
        }
        
        $this->cache($refl, $meta);
        
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

        $regex = '/^\s*(?:\/\*)?\*\s*@(\S+)(?:[ \t]+(\S.*?))?(?:\*\*\/)?$/m';
        
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
}
