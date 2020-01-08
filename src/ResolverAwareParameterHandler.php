<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ResolverAwareParameterHandler
{
    public function setResolver(InteractiveParameterResolverInterface $interactiveParameterResolver);
}
