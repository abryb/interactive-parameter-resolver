<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Abryb\InteractiveParameterResolver\TypeResolver\FirstTypeResolver;
use Abryb\InteractiveParameterResolver\Util\Util;
use Abryb\ParameterInfo\ParameterInfoExtractorFactory;
use Abryb\ParameterInfo\ParameterInfoExtractorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class InteractiveParameterResolver implements InteractiveParameterResolverInterface
{
    /**
     * @var QuestionHelper
     */
    private $questionHelper;
    /**
     * @var iterable|ParameterHandlerInterface[]
     */
    private $handlers;

    /**
     * @var ParameterInfoExtractorInterface
     */
    private $parameterInfoExtractor;
    /**
     * @var TypeResolverInterface
     */
    private $typeResolver;

    /**
     * InteractiveParameterResolver constructor.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param ParameterHandlerInterface[] $handlers
     * @param TypeResolverInterface $typeResolver
     * @param ParameterInfoExtractorInterface|null $parameterInfoExtractor
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        iterable $handlers = [],
        TypeResolverInterface $typeResolver = null,
        ParameterInfoExtractorInterface $parameterInfoExtractor = null
    )
    {
        Assert::allIsInstanceOf($handlers, ParameterHandlerInterface::class);
        $this->questionHelper         = new QuestionHelper($input, $output);
        $this->handlers               = $handlers;
        $this->parameterInfoExtractor = $parameterInfoExtractor ?: ParameterInfoExtractorFactory::create();
        $this->typeResolver           = $typeResolver ?: new FirstTypeResolver();
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws AbrybInteractiveParameterResolverException
     */
    public function askParameter(\ReflectionParameter $parameter)
    {
        $parameterInfo = $this->parameterInfoExtractor->getParameterInfo($parameter);

        $type = $this->typeResolver->resolveType($parameterInfo, $this->questionHelper);

        $parameter = new Parameter(
            $parameterInfo->getReflection(),
            $type,
            $parameterInfo->getDescription()
        );

        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($parameter)) {
                return $handler->handle($parameter, $this->questionHelper);
            }
        }

        throw new AbrybInteractiveParameterResolverException(sprintf(
            "Could not find handler for parameter %s of method %s",
            $parameter->getReflectionParameter()->getName(),
            Util::stringifyReflectionParameterFunction($parameter->getReflectionParameter())
        ));
    }
}
