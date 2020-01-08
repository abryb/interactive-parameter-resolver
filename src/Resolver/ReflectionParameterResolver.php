<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Resolver;

use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ReflectionParameterResolverInterface;
use Abryb\ParameterInfo\ParameterInfoExtractorInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class ReflectionParameterResolver implements ReflectionParameterResolverInterface
{
    /**
     * @var ParameterInfoExtractorInterface
     */
    private $parameterInfoExtractor;
    /**
     * @var TypeResolverInterface
     */
    private $typeResolver;

    public function __construct(
        ParameterInfoExtractorInterface $parameterInfoExtractor,
        TypeResolverInterface $typeResolver
    )
    {
        $this->parameterInfoExtractor = $parameterInfoExtractor;
        $this->typeResolver           = $typeResolver;
    }

    public function resolveReflectionParameter(\ReflectionParameter $parameter): Parameter
    {
        $parameterInfo = $this->parameterInfoExtractor->getParameterInfo($parameter);

        $type = $this->typeResolver->resolveType($parameterInfo);

        return new Parameter(
            $parameterInfo->getReflection()->getName(),
            $type,
            $parameterInfo->getReflection()->getDefaultValue(),
            $parameterInfo->getDescription()
        );
    }
}
