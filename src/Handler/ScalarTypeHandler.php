<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver\Handler;

use Abryb\InteractiveParameterResolver\IO;
use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class ScalarTypeHandler extends AbstractHandler implements ParameterHandlerInterface
{
    public function canHandle(Parameter $parameter): bool
    {
        $type =  $parameter->getType()->getBuiltinType();

        return in_array($type, [Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_INT, Type::BUILTIN_TYPE_FLOAT, Type::BUILTIN_TYPE_STRING]);
    }

    public function handle(Parameter $parameter, StyleInterface $io)
    {
        if ($parameter->getDescription()) {
            $io->text($parameter->getDescription());
        }

        return parent::handle($parameter, $io);
    }

    protected function doHandle(Parameter $parameter, StyleInterface $io)
    {
        switch ($parameter->getType()->getBuiltinType()) {
            case Type::BUILTIN_TYPE_BOOL:
                $value =  $this->askBool($parameter, $io);
                break;
            case Type::BUILTIN_TYPE_INT:
                $value =  $this->askInt($parameter, $io);
                break;
            case Type::BUILTIN_TYPE_FLOAT:
                $value =  $this->askFloat($parameter, $io);
                break;
            case Type::BUILTIN_TYPE_STRING:
                $value =  $this->askString($parameter, $io);
                break;
            default:
                throw new \LogicException("Invalid parameter supplied to ".__METHOD__);
        }

        return $value;
    }

    private function askBool(Parameter $parameter, StyleInterface $io) : ?bool
    {
        $choices = ['true' => true, 'false' => false, 'null' => null];

        $regexps = [
            'true' => [
                '^t',
                '^y'
            ],
            'false' => [
                '^n$',
                '^no',
                '^f',
            ],
            'null' => [
                '^null',
                '^\s*$',
            ]
        ];

        $value = $this->askParameter($io, $parameter);

        if (null === $value) {
            return $value;
        }

        foreach ($regexps as $key => $expressions) {
            foreach ($expressions as $expression) {
                if (preg_match("#$expression#", $value)) {
                    return $choices[$key];
                }
            }
        }

        $io->warning("Sorry but value '$value' couldn't be matched with true false or null. Please try again.");
        return $this->askBool($parameter, $io);
    }

    private function askInt(Parameter $parameter, StyleInterface $io) : ?int
    {
        $result = $this->askParameter($io, $parameter);

        return is_null($result) ? $result : (int) $result;
    }

    private function askFloat(Parameter $parameter, StyleInterface $io) : ?float
    {
        $result = $this->askParameter($io, $parameter);

        return is_null($result) ? $result : (float) $result;
    }

    private function askString(Parameter $parameter, StyleInterface $io) : ?string
    {
        $result = $this->askParameter($io, $parameter);

        return is_null($result) ? $result : (string) $result;
    }
}
