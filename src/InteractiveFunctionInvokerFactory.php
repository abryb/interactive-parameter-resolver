<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver;


use Abryb\InteractiveParameterResolver\Handler\CollectionTypeHandler;
use Abryb\InteractiveParameterResolver\Handler\DateTimeHandler;
use Abryb\InteractiveParameterResolver\Handler\ObjectTypeHandler;
use Abryb\InteractiveParameterResolver\Handler\ScalarTypeHandler;
use Abryb\InteractiveParameterResolver\ParameterFactory\FirstTypeResolver;
use Abryb\InteractiveParameterResolver\ParameterFactory\ParameterFactory;
use Abryb\ParameterInfo\ParameterInfoExtractorFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class InteractiveFunctionInvokerFactory
{
    public static function createInvoker(
        StyleInterface $ioStyle,
        array $additionalHandler = []
    ) : InteractiveFunctionInvokerInterface
    {
        $parameterFactory = new ParameterFactory(
            ParameterInfoExtractorFactory::create(),
            new FirstTypeResolver()
        );

        $parameterResolver =  new InteractiveParameterResolver(
            $ioStyle,
            array_merge($additionalHandler, [
                new ScalarTypeHandler(),
                new DateTimeHandler(),
                new ObjectTypeHandler($parameterFactory),
                new CollectionTypeHandler(),
            ])
        );

        return new InteractiveFunctionInvoker($parameterResolver, $parameterFactory);
    }
}
