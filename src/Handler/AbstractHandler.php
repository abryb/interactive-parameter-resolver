<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver\Handler;


use Abryb\InteractiveParameterResolver\Parameter;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
abstract class AbstractHandler
{
    public function askParameter(StyleInterface $io, Parameter $parameter)
    {
        $string = sprintf("%s (<fg=white>%s</>)", $parameter->getName(), $parameter->getTypeString());
        return $io->ask($string, $parameter->getDefaultValue());
    }

    public function handle(Parameter $parameter, StyleInterface $io)
    {
        if ($parameter->getDescription()) {
            $io->note($parameter->getDescription());
        }
        $value = $this->doHandle($parameter, $io);

        while (null === $value && !$parameter->getType()->isNullable()) {
            $io->warning("I'm sorry but this value can't be null.");
            $value = $this->doHandle($parameter, $io);
        }

        return $value;
    }

    abstract protected function doHandle(Parameter $parameter, StyleInterface $io);
}