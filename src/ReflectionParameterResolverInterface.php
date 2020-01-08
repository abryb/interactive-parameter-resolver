<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ReflectionParameterResolverInterface
{
    public function resolveReflectionParameter(\ReflectionParameter $reflectionParameter): Parameter;
}
