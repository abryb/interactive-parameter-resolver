<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ParameterHandlerWithResolverInterface;
use Abryb\InteractiveParameterResolver\Resolver\TypeResolverInterface;
use Abryb\ParameterInfo\ParameterInfoExtractorInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class ObjectTypeHandler implements ParameterHandlerInterface, ParameterHandlerWithResolverInterface
{
    use ResolverTrait;

    public function canHandle(Parameter $parameter): bool
    {
        return
            'object' === $parameter->getType()->getBuiltinType()
            &&
            null !== $parameter->getType()->getClassName();
    }

    /**
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function handle(Parameter $parameter, IO $IO)
    {
        $class = $parameter->getType()->getClassName();

        if (!class_exists($class)) {
            throw new AbrybInteractiveParameterResolverException("Class {$class} does not exists!");
        }

        return $this->resolver->constructObject($class);
    }
}
