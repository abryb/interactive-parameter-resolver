<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface InteractiveParameterResolverInterface
{
    public function askParameter(\ReflectionParameter $parameter);
}
