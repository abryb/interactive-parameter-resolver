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
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
final class InteractiveParameterResolverFactory
{
    public static function createResolver(
        StyleInterface $ioStyle,
        array $additionalHandler = []
    ) : InteractiveParameterResolverInterface
    {
        $parameterFactory = new ParameterFactory(
            ParameterInfoExtractorFactory::create(),
            new FirstTypeResolver()
        );

        return new InteractiveParameterResolver(
            $ioStyle,
            array_merge($additionalHandler, [
                new ScalarTypeHandler(),
                new DateTimeHandler(),
                new ObjectTypeHandler($parameterFactory),
                new CollectionTypeHandler(),
            ])
        );
    }
}
