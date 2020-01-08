<?php

declare(strict_types=1);


namespace Abryb\InteractiveParameterResolver\Handler;


use Abryb\InteractiveParameterResolver\Parameter;
use Abryb\InteractiveParameterResolver\ParameterHandlerInterface;
use Abryb\InteractiveParameterResolver\ResolverAwareParameterHandler;
use Abryb\ParameterInfo\Type;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class DateTimeHandler extends AbstractHandler implements ParameterHandlerInterface, ResolverAwareParameterHandler
{
    use ResolverTrait;

    private $formats;

    /**
     * DateTimeHandler constructor.
     * @param array $formats = [
     *     'Y-m-d' => "^\d\d\d\d-\d\d-\d\d$",
     *     'Y-m-d H:i:s' => '^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$'
     * ];
     * array of key value, where key is php date format and value regex checking if user input is that format
     */
    public function __construct(array $formats = [])
    {
        if (empty($formats)) {
            $formats = [
                'Y-m-d' => "^\d\d\d\d-\d\d-\d\d$",
                'Y-m-d H:i:s' => '^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$'
            ];
        }

        $this->formats = $formats;
    }

    public function canHandle(Parameter $parameter): bool
    {
        return
            $parameter->getType()->getBuiltinType() === Type::BUILTIN_TYPE_OBJECT
            &&
            in_array($parameter->getType()->getClassName(), [\DateTime::class, \DateTimeInterface::class]);
    }

    public function handle(Parameter $parameter, StyleInterface $io)
    {
        $io->note(sprintf(
            "Parameter %s is %s. Please pass datetime in one of format: %s.",
            $parameter->getName(),
            $parameter->getType()->getClassName(),
            implode(", ", array_map(function($format) {
                return "'$format'";
            }, array_keys($this->formats)))
        ));

        return parent::handle($parameter, $io);
    }

    protected function doHandle(Parameter $parameter, StyleInterface $io)
    {
        $value = $this->askParameter($io, $parameter);

        if (null === $value) {
           return null;
        }

        foreach ($this->formats as $format => $regex) {
            if (preg_match("#{$regex}#", $value)) {
                $d = \DateTime::createFromFormat($format, $value);
                if ($d->format($format) === $value) {
                    return $d;
                } else {
                    $io->warning("Value '$value' is not valid date in format '$format'.");
                    return $this->askParameter($io, $parameter);
                }
            }
        }

        $io->warning("Value $value is not valid date time string in any of formats. Please try again.");
        return $this->askParameter($io, $parameter);
    }
}