<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver;

use Abryb\ParameterInfo\Type;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class InteractiveFunctionInvoker implements InteractiveFunctionInvokerInterface
{
    /**
     * @var InteractiveParameterResolverInterface
     */
    private $interactiveParameterResolver;

    /**
     * @var ParameterFactoryInterface
     */
    private $parameterFactory;

    public function __construct(
        InteractiveParameterResolverInterface $interactiveParameterResolver,
        ParameterFactoryInterface $parameterFactory
    )
    {
        $this->interactiveParameterResolver = $interactiveParameterResolver;
        $this->parameterFactory             = $parameterFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function invokeFunction(string $function)
    {
        $reflection = new \ReflectionFunction($function);

        return $reflection->invokeArgs($this->getArgumentsForReflectionFunctionAbstract($reflection));
    }

    /**
     * {@inheritDoc}
     */
    public function invokeMethod(object $object, string $method)
    {
        $reflectionMethod = new \ReflectionMethod($object, $method);

        return $reflectionMethod->invokeArgs($object, $this->getArgumentsForReflectionFunctionAbstract($reflectionMethod));
    }

    /**
     * {@inheritDoc}
     */
    public function invokeStaticMethod(string $class, string $method)
    {
        $reflectionMethod = new \ReflectionMethod($class, $method);

        return $reflectionMethod->invokeArgs(null, $this->getArgumentsForReflectionFunctionAbstract($reflectionMethod));
    }

    /**
     * {@inheritDoc}
     */
    public function constructObject(string $class): object
    {
        $reflectionClass = new \ReflectionClass($class);

        $args = $this->getArgumentsForReflectionFunctionAbstract($reflectionClass->getConstructor());

        return $reflectionClass->newInstanceArgs($args);
    }

    /**
     * {@inheritDoc}
     */
    public function getArgumentsForReflectionFunctionAbstract(\ReflectionFunctionAbstract $function): array
    {
        $arguments = [];
        foreach ($function->getParameters() as $reflectionParameter) {

            $parameter = $this->parameterFactory->createParameterFromReflection($reflectionParameter);

            $value = $this->interactiveParameterResolver->resolveParameter($parameter);
            if (!$reflectionParameter->isVariadic()) {
                $arguments[] = $value;
            } else {
                $arguments = array_merge($arguments, $value);
            }
        }

        return $arguments;
    }
}