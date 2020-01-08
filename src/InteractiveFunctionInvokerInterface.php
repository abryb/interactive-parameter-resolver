<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver;


use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface InteractiveFunctionInvokerInterface
{
    /**
     * @param string $function
     * @return mixed
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function invokeFunction(string $function);

    /**
     * @param object $object
     * @param string $method
     * @return mixed
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function invokeMethod(object $object, string $method);

    /**
     * @param string $class
     * @param string $method
     * @return mixed
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function invokeStaticMethod(string $class, string $method);

    /**
     * @param string $class
     * @return object
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function constructObject(string $class) : object;

    /**
     * @param \ReflectionFunctionAbstract $function
     * @return array
     * @throws AbrybInteractiveParameterResolverException
     * @throws \ReflectionException
     */
    public function getArgumentsForReflectionFunctionAbstract(\ReflectionFunctionAbstract $function): array;
}