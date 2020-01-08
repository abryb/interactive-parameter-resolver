<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\ParameterFactory;

use Abryb\ParameterInfo\ParameterInfo;
use Abryb\ParameterInfo\Type;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class FirstTypeResolver implements TypeResolverInterface
{
    public function resolveType(ParameterInfo $info): Type
    {
        return current($info->getTypes());
    }
}
