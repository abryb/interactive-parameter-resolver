<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface InteractiveParameterResolverInterface
{
    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function resolveParameter(Parameter $parameter);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function resolveReflectionParameter(\ReflectionParameter $reflectionParameter);

    /**
     * @throws AbrybInteractiveParameterResolverException
     * @param object|null $object pass null for static methods
     * @return mixed anything
     */
    public function invokeReflectionMethod(\ReflectionMethod $method, ?object $object);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function invokeReflectionFunction(\ReflectionFunction $function);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function constructObject(string $class);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @param \ReflectionFunctionAbstract $abstract
     * @return array of mixed values
     */
    public function getArgumentsForReflectionFunctionAbstract(\ReflectionFunctionAbstract $abstract) : array;
}
