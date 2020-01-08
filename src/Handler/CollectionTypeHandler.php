<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ParameterHandlerWithResolverInterface;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class CollectionTypeHandler implements ParameterHandlerInterface, ParameterHandlerWithResolverInterface
{
    use ResolverTrait;

    public function canHandle(Parameter $parameter): bool
    {
        return
            in_array($parameter->getType()->getBuiltinType(), [Type::BUILTIN_TYPE_ITERABLE, Type::BUILTIN_TYPE_ARRAY])
            && $parameter->getType()->isCollection()
            && $parameter->getType()->getCollectionValueType();
    }

    public function handle(Parameter $parameter, IO $IO)
    {
        $innerType = $parameter->getType()->getCollectionValueType();
        $IO->getOutput()->writeln("{$parameter->getName()} is collection.");

        $elements = [];

        $count = 0;
        while ($IO->ask(new ConfirmationQuestion('Do you want to add an element? (y/N): ', false))) {
            $parameterChild = new Parameter(
                "{$parameter->getName()}[{$count}]",
                $innerType,
            );
            $elements[] = $this->resolver->resolveParameter($parameterChild);
        }

        return $elements;
    }
}
