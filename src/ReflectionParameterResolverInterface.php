<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ReflectionParameterResolverInterface
{
    /**
     * @throws AbrybInteractiveParameterResolverException
     */
    public function resolveReflectionParameter(\ReflectionParameter $reflectionParameter): Parameter;
}
