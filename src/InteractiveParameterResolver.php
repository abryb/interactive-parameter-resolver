<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Webmozart\Assert\Assert;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class InteractiveParameterResolver implements InteractiveParameterResolverInterface
{
    /**
     * @var IO
     */
    private $io;

    /**
     * @var iterable|ParameterHandlerInterface[]
     */
    private $handlers;

    /**
     * @var ReflectionParameterResolverInterface
     */
    private $reflectionParameterResolver;

    /**
     * InteractiveParameterResolver constructor.
     *
     * @param ParameterHandlerInterface[] $handlers
     */
    public function __construct(
        IO $io,
        iterable $handlers,
        ReflectionParameterResolverInterface $reflectionParameterResolver
    )
    {
        Assert::allIsInstanceOf($handlers, ParameterHandlerInterface::class);
        $this->io                          = $io;
        $this->handlers                    = $handlers;
        $this->reflectionParameterResolver = $reflectionParameterResolver;
    }

    /**
     * @throws AbrybInteractiveParameterResolverException
     */
    public function resolveParameter(Parameter $parameter)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($parameter)) {
                if ($handler instanceof ParameterHandlerWithResolverInterface) {
                    $handler->setResolver($this);
                }

                return $handler->handle($parameter, $this->io);
            }
        }

        throw new AbrybInteractiveParameterResolverException(sprintf(
            'Could not find handler for parameter %s of type %s',
            $parameter->getName(),
            $parameter->getType()->getBuiltinType()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function resolveReflectionParameter(\ReflectionParameter $reflectionParameter)
    {
        return $this->resolveParameter($this->reflectionParameterResolver->resolveReflectionParameter($reflectionParameter));
    }

    /**
     * {@inheritdoc}
     */
    public function invokeReflectionMethod(\ReflectionMethod $method, ?object $object)
    {
        if (!$method->isStatic() && !$object) {
            throw new AbrybInteractiveParameterResolverException(sprintf("Can't invoke not static function %s without object.", $method->getName()));
        }


        $arguments = [];
        foreach ($method->getParameters() as $reflectionParameter) {
            if (!$reflectionParameter->isVariadic()) {
                $arguments[] = $this->resolveReflectionParameter($reflectionParameter);
            } else {
                $variadic  = $this->resolveReflectionParameter($reflectionParameter);
                $arguments = array_merge($arguments, $variadic);
            }
        }

        $method->invokeArgs($object, $this->getArgumentsForReflectionFunctionAbstract($method));
    }

    /**
     * {@inheritdoc}
     */
    public function invokeReflectionFunction(\ReflectionFunction $function)
    {
        $function->invokeArgs($this->getArgumentsForReflectionFunctionAbstract($function));
    }

    /**
     * {@inheritdoc}
     */
    public function constructObject(string $class)
    {
        try {
            $r = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new AbrybInteractiveParameterResolverException('Reflection exception.', 0, $e);
        }

        return $r->newInstanceArgs($this->getArgumentsForReflectionFunctionAbstract($r->getMethod('__construct')));
    }

    /**
     * {@inheritdoc}
     */
    public function getArgumentsForReflectionFunctionAbstract(\ReflectionFunctionAbstract $function): array
    {
        $arguments = [];
        foreach ($function->getParameters() as $reflectionParameter) {
            $value = $this->resolveReflectionParameter($reflectionParameter);
            if (!$reflectionParameter->isVariadic()) {
                $arguments[] = $value;
            } else {
                $arguments = array_merge($arguments, $value);
            }
        }

        return $arguments;
    }
}
