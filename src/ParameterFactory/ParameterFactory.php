<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\ParameterFactory;

use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterFactoryInterface;
use Abryb\ParameterInfo\ParameterInfoExtractorInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class ParameterFactory implements ParameterFactoryInterface
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

    /**
     * {@inheritdoc}
     */
    public function createParameterFromReflection(\ReflectionParameter $parameter): Parameter
    {
        $parameterInfo = $this->parameterInfoExtractor->getParameterInfo($parameter);

        $type = $this->typeResolver->resolveType($parameterInfo);

        try {
            $defaultValue = $parameterInfo->getReflection()->getDefaultValue();
        } catch (\ReflectionException $e) {
            $defaultValue = null;
        }

        return new Parameter(
            $parameterInfo->getReflection()->getName(),
            $type,
            $defaultValue,
            $parameterInfo->getDescription()
        );
    }
}
