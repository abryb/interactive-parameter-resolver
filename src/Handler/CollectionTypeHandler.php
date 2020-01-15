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

        if (in_array($innerType->getBuiltinType(), [Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_INT, Type::BUILTIN_TYPE_FLOAT, Type::BUILTIN_TYPE_STRING])) {
            return $this->handleArrayOfScalar($parameter, $io, $innerType);
        }

        $io->note(sprintf(
            "%s is collection of type %s.",
            $parameter->getName(),
            $innerType->getClassName() ? $innerType->getClassName() : $innerType->getBuiltinType()
        ));

        $elements = [];
        $count = 0;
        $question = sprintf('</>Do you want to add an element to <info>%s</info>?: ', $parameter->getName());
        while ($io->askQuestion(new ConfirmationQuestion(sprintf($question), $count === 0))) {
            $childName = "{$parameter->getName()}[{$count}]";
            $parameterChild = new Parameter(
                $childName,
                $innerType,
            );
            $elements[] = $this->resolver->resolveParameter($parameterChild);
            $io->text(sprintf("%s has been created.", $childName));
            $question = sprintf('</>Do you want to add another element to <info>%s</info>?: ', $parameter->getName());
        }

        return $elements;
    }

    private function handleArrayOfScalar(Parameter $parameter, StyleInterface $io, Type $innerType)
    {
        $io->note(sprintf("%s is collection of %s. Pass null to stop adding to collection.", $parameter->getName(), $parameter->getTypeString()));

        $elements = [];
        $count = 0;
        $last = true;

        while ($last !== null) {
            $parameterChild = new Parameter(
                "{$parameter->getName()}[{$count}]",
                new Type(
                    $innerType->getBuiltinType(),
                    true,
                ),

            );
            $elements[] = $last = $this->resolver->resolveParameter($parameterChild);
            $count ++;
        }

        return $elements;
    }
}
