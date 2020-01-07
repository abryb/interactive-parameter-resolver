<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver\Handler;


use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ParameterHandlerWithResolverInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class ObjectTypeHandler implements ParameterHandlerInterface, ParameterHandlerWithResolverInterface
{
    use ResolverTrait;

    public function canHandle(Parameter $parameter): bool
    {
        return
            $parameter->getType()->getBuiltinType() === 'object'
            &&
            $parameter->getType()->getClassName() !== null;
    }

    /**
     * @param Parameter $parameter
     * @param IO $IO
     * @return mixed
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function handle(Parameter $parameter, IO $IO)
    {
        $class = $parameter->getType()->getClassName();

        if (!class_exists($class)) {
            throw new AbrybInteractiveParameterResolverException("Class $class does not exists!");
        }

        $refClass = new \ReflectionClass($class);

        $constructorRef = $refClass->getMethod('__construct');

        $params = [];

        foreach ($constructorRef->getParameters() as $param) {
            $value = $this->resolver->askParameter($param);

            if ($param->isVariadic()) {
                $params = array_merge($params, $value);
            } else {
                $params[] = $value;
            }
        }



        return new $class(...$params);
    }
}