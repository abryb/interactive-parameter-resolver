<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
interface ParameterHandlerInterface
{
    public function canHandle(Parameter $parameter): bool;

    public function handle(Parameter $parameter, IO $IO);
}
