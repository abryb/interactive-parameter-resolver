<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver\Handler;


use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ParameterHandlerWithResolverInterface;
use Abryb\ParameterInfo\Type;

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
    }
}