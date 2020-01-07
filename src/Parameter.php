<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\ParameterInfo\Type;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class Parameter
{
    /**
     * @var Type
     */
    private $type;
    /**
     * @var string
     */
    private $description;

    /**
     * @var \ReflectionParameter
     */
    private $reflectionParameter;

    /**
     * Parameter constructor.
     */
    public function __construct(\ReflectionParameter $reflectionParameter, Type $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;
        $this->reflectionParameter = $reflectionParameter;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getReflectionParameter(): \ReflectionParameter
    {
        return $this->reflectionParameter;
    }

    public function getDefaultValue()
    {
        return $this->reflectionParameter->getDefaultValue();
    }

    public function getName()
    {
        return $this->reflectionParameter->getName();
    }
}
