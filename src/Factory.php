<?php

namespace Jasny\Meta;

use Reflector;
use Jasny\Meta;

/**
 * Meta factory
 */
interface Factory
{
    /**
     * Get metadata
     *
     * @param \ReflectionClass|\ReflectionProperty|\ReflectionMethod $refl
     * @return Meta
     */
    public function create(Reflector $refl);
}
