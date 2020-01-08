<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ParameterFactoryInterface
{
    /**
     * @throws AbrybInteractiveParameterResolverException
     */
    public function createParameterFromReflection(\ReflectionParameter $reflectionParameter): Parameter;
}
