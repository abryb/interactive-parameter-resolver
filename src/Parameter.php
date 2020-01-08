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
     * @var string
     */
    private $name;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var string|null
     */
    private $description;

    private $defaultValue;

    /**
     * Parameter constructor.
     *
     * @param mixed|null $defaultValue
     */
    public function __construct(string $name, Type $type, $defaultValue = null, ?string $description = null)
    {
        $this->name         = $name;
        $this->type         = $type;
        $this->defaultValue = $defaultValue;
        $this->description  = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
