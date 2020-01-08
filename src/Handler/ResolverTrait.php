<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\InteractiveParameterResolverInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
trait ResolverTrait
{
    /**
     * @var InteractiveParameterResolverInterface|null
     */
    private $resolver;

    public function setResolver(InteractiveParameterResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }
}
