<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\TypeResolver;

use Abryb\InteractiveParameterResolver\QuestionHelper;
use Abryb\InteractiveParameterResolver\TypeResolverInterface;
use Abryb\ParameterInfo\ParameterInfo;
use Abryb\ParameterInfo\Type;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class FirstTypeResolver implements TypeResolverInterface
{
    public function resolveType(ParameterInfo $info, QuestionHelper $helper): Type
    {
        return current($info->getTypes());
    }
}
