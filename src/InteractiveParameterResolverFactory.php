<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver;


use Abryb\InteractiveParameterResolver\Handler\CollectionTypeHandler;
use Abryb\InteractiveParameterResolver\Handler\ObjectTypeHandler;
use Abryb\InteractiveParameterResolver\Handler\ScalarTypeHandler;
use Abryb\InteractiveParameterResolver\Resolver\FirstTypeResolver;
use Abryb\InteractiveParameterResolver\Resolver\ReflectionParameterResolver;
use Abryb\ParameterInfo\ParameterInfoExtractorFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class InteractiveParameterResolverFactory
{
    public function createResolver(
        InputInterface $input,
        OutputInterface $output,
        array $additionalHandler = []
    )
    {
        return new InteractiveParameterResolver(
            new IO($input, $output),
            array_merge($additionalHandler, [
                new ScalarTypeHandler(),
                new ObjectTypeHandler(),
                new CollectionTypeHandler()
            ]),
            new ReflectionParameterResolver(
                ParameterInfoExtractorFactory::create(),
                new FirstTypeResolver()
            )
        );
    }
}