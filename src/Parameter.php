<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Abryb\ParameterInfo\Type;
use Webmozart\Assert\Assert;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class Parameter
{
    /**
     * @var string|null
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

    /**
     * @var null|bool|int|float|string
     */
    private $defaultValue;

    /**
     * Parameter constructor.
     */
    public function __construct(?string $name, Type $type, $defaultValue = null, ?string $description = null)
    {
        $this->name         = $name;
        $this->type         = $type;
        $this->defaultValue = $defaultValue;
        $this->description  = $description;
    }

    public function getName(): ?string
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

    /**
     * @return bool|float|int|string|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue() : bool
    {
        return $this->getDefaultValue() || $this->getDefaultValue() === null && $this->getType()->isNullable();
    }

    public function getTypeString() : string
    {
        if ($this->getType()->getBuiltinType() === Type::BUILTIN_TYPE_OBJECT && $this->getType()->getClassName()) {
            return $this->getType()->getClassName();
        }
        return $this->getType()->getBuiltinType();
    }

    public function withName(string $name) : Parameter
    {
        $p = clone $this;
        $p->name = $name;
        return $p;
    }
}
