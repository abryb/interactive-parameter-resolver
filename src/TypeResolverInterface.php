<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\ParameterInfo\ParameterInfo;
use Abryb\ParameterInfo\Type;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface TypeResolverInterface
{
    public function resolveType(ParameterInfo $info, QuestionHelper $helper): Type;
}
