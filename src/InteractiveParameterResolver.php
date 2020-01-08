<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\InteractiveParameterResolver\Exception\AbrybInteractiveParameterResolverException;
use Symfony\Component\Console\Style\StyleInterface;
use Webmozart\Assert\Assert;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class InteractiveParameterResolver implements InteractiveParameterResolverInterface
{
    /**
     * @var StyleInterface
     */
    private $io;

    /**
     * @var iterable|ParameterHandlerInterface[]
     */
    private $handlers;

    /**
     * InteractiveParameterResolver constructor.
     *
     * @param ParameterHandlerInterface[] $handlers
     */
    public function __construct(
        StyleInterface $io,
        iterable $handlers
    )
    {
        Assert::allIsInstanceOf($handlers, ParameterHandlerInterface::class);
        $this->io                          = $io;
        $this->handlers                    = $handlers;
    }

    /**
     * @throws AbrybInteractiveParameterResolverException
     */
    public function resolveParameter(Parameter $parameter)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($parameter)) {
                if ($handler instanceof ResolverAwareParameterHandler) {
                    $handler->setResolver($this);
                }

                return $handler->handle($parameter, $this->io);
            }
        }

        throw new AbrybInteractiveParameterResolverException(sprintf(
            'Could not find handler for parameter %s of type %s',
            $parameter->getName(),
            $parameter->getType()->getBuiltinType()
        ));
    }
}
