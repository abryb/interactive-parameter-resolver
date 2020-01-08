<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Abryb\InteractiveParameterResolver\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
    public function askParameter(Parameter $parameter)
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
            'Could not find handler for parameter %s of method %s',
            $parameter->getName(),
            Util::stringifyReflectionParameterFunction($parameter->getReflectionParameter())
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function askForReflectionParameter(\ReflectionParameter $reflectionParameter)
    {
        return $this->askParameter($this->reflectionParameterResolver->resolveReflectionParameter($reflectionParameter));
    }

    /**
     * {@inheritdoc}
     */
    public function invokeMethod(\ReflectionMethod $method, object $object)
    {
        $arguments = [];
        foreach ($method->getParameters() as $reflectionParameter) {
            if (!$reflectionParameter->isVariadic()) {
                $arguments[] = $this->askForReflectionParameter($reflectionParameter);
            } else {
                $variadic  = $this->askForReflectionParameter($reflectionParameter);
                $arguments = array_merge($arguments, $variadic);
            }
        }

        $method->invokeArgs($object, $this->getArgumentsForReflectionFunctionAbstract($method));
    }

    /**
     * {@inheritdoc}
     */
    public function invokeFunction(\ReflectionFunction $function)
    {
        $function->invokeArgs($this->getArgumentsForReflectionFunctionAbstract($function));
    }

    /**
     * {@inheritdoc}
     */
    public function constructObject(string $class)
    {
        $r = new \ReflectionClass($class);

        return $r->newInstanceArgs($this->getArgumentsForReflectionFunctionAbstract($r->getMethod('__construct')));
    }

    private function getArgumentsForReflectionFunctionAbstract(\ReflectionFunctionAbstract $function): array
    {
        $arguments = [];
        foreach ($function->getParameters() as $reflectionParameter) {
            $value = $this->askForReflectionParameter($reflectionParameter);
            if (!$reflectionParameter->isVariadic()) {
                $arguments[] = $value;
            } else {
                $arguments = array_merge($arguments, $value);
            }
        }

        return $arguments;
    }
}
