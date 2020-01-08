<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterFactoryInterface;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ResolverAwareParameterHandler;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class ObjectTypeHandler implements ParameterHandlerInterface, ResolverAwareParameterHandler
{
    use ResolverTrait;

    /**
     * @var ParameterFactoryInterface
     */
    private $parameterFactory;

    public function __construct(ParameterFactoryInterface $parameterFactory)
    {
        $this->parameterFactory = $parameterFactory;
    }

    public function canHandle(Parameter $parameter): bool
    {
        return
            Type::BUILTIN_TYPE_OBJECT === $parameter->getType()->getBuiltinType()
            &&
            null !== $parameter->getType()->getClassName();
    }

    /**
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function handle(Parameter $parameter, StyleInterface $io)
    {
        $class = $parameter->getType()->getClassName();

        if (!class_exists($class)) {
            throw new AbrybInteractiveParameterResolverException("Class {$class} does not exists!");
        }

        $classRef = new \ReflectionClass($class);
        $constructorRef = $classRef->getConstructor();

        $args = [];
        foreach ($constructorRef->getParameters() as $key => $refParam) {
            $constructorParameter = $this->parameterFactory->createParameterFromReflection($refParam);

            $constructorParameter = $constructorParameter->withName("{$parameter->getName()}.{$constructorParameter->getName()}");
            if ($refParam->isVariadic()) {
                $args = array_merge($args, $this->resolver->resolveParameter($constructorParameter));
            } else {
                $args[] = $this->resolver->resolveParameter($constructorParameter);
            }
        }
        return $classRef->newInstanceArgs($args);
    }
}
