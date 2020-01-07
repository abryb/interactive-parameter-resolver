<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ParameterDecoratorInterface
{
    public function decorate(Parameter $parameter, QuestionHelper $helper): Parameter;
}
