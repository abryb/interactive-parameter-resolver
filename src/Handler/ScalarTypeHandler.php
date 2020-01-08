<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Question\Question;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class ScalarTypeHandler implements ParameterHandlerInterface
{
    public function canHandle(Parameter $parameter): bool
    {
        $type =  $parameter->getType()->getBuiltinType();

        return in_array($type, [Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_INT, Type::BUILTIN_TYPE_FLOAT, Type::BUILTIN_TYPE_STRING]);
    }

    public function handle(Parameter $parameter, IO $IO)
    {
        switch ($parameter->getType()->getBuiltinType()) {
            case Type::BUILTIN_TYPE_BOOL:
                return $this->askBool($parameter, $IO);
            case Type::BUILTIN_TYPE_INT:
                return $this->askInt($parameter, $IO);
            case Type::BUILTIN_TYPE_FLOAT:
                return $this->askFloat($parameter, $IO);
            case Type::BUILTIN_TYPE_STRING:
                return $this->askString($parameter, $IO);
        }
    }

    private function askBool(Parameter $parameter, IO $IO)
    {
        $question = new Question("{$parameter}:", null);

        $result = $IO->ask($question);

        return is_null($result) ? $result : (bool) $result;
    }

    private function askInt(Parameter $parameter, IO $IO)
    {
        $question = new Question($parameter->getName(), $parameter->getDefaultValue());

        $result = $IO->ask($question);

        return is_null($result) ? $result : (int) $result;
    }

    private function askFloat(Parameter $parameter, IO $IO)
    {
        $question = new Question($parameter->getName(), $parameter->getDefaultValue());

        $result = $IO->ask($question);

        return is_null($result) ? $result : (float) $result;
    }

    private function askString(Parameter $parameter, IO $IO)
    {
        $question = new Question($parameter->getName(), $parameter->getDefaultValue());

        $result = $IO->ask($question);

        return is_null($result) ? $result : (string) $result;
    }
}
