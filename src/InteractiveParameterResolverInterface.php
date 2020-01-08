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
    public function askParameter(Parameter $parameter);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function askForReflectionParameter(\ReflectionParameter $reflectionParameter);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function invokeMethod(\ReflectionMethod $method, object $object);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function invokeFunction(\ReflectionFunction $function);

    /**
     * @throws AbrybInteractiveParameterResolverException
     *
     * @return mixed anything
     */
    public function constructObject(string $class);
}
