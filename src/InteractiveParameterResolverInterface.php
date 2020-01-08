<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface InteractiveParameterResolverInterface
{
    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function resolveParameter(Parameter $parameter);
}
