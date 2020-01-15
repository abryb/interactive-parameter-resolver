<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ResolverAwareParameterHandler;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class CollectionTypeHandler implements ParameterHandlerInterface, ResolverAwareParameterHandler
{
    use ResolverTrait;

    public function canHandle(Parameter $parameter): bool
    {
        return
            in_array($parameter->getType()->getBuiltinType(), [Type::BUILTIN_TYPE_ITERABLE, Type::BUILTIN_TYPE_ARRAY])
            && $parameter->getType()->isCollection()
            && $parameter->getType()->getCollectionValueType();
    }

    public function handle(Parameter $parameter, StyleInterface $io)
    {
        $innerType = $parameter->getType()->getCollectionValueType();
        $io->writeln(sprintf(
            "%s is collection of type %s.",
            $parameter->getName(),
            $innerType->getClassName() ? $innerType->getClassName() : $innerType->getBuiltinType()
        ));

        $elements = [];

        $count = 0;
        while ($io->askQuestion(new ConfirmationQuestion('Do you want to add an element?: ', $count === 0))) {
            $parameterChild = new Parameter(
                "{$parameter->getName()}[{$count}]",
                $innerType,
            );
            $elements[] = $this->resolver->resolveParameter($parameterChild);
        }

        return $elements;
    }

}
