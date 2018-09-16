<?php

namespace Jasny\Meta;

/**
 * Factory for getting meta
 */
interface FactoryInterface
{
    public function forClass(string $class): MetaClass;
}
