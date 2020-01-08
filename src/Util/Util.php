<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Util;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class Util
{
    public static function stringifyReflectionParameterFunction(\ReflectionParameter $reflectionParameter): string
    {
        if ($reflectionParameter->getDeclaringClass()) {
            $namespace= $reflectionParameter->getDeclaringClass()->getName();
        } else {
            $namespace = $reflectionParameter->getDeclaringFunction()->getName();
        }

        return $namespace.'::'.$reflectionParameter->getDeclaringFunction()->getName();
    }
}
