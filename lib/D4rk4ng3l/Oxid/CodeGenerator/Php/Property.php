<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 09:51
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Property implements GeneratorInterface
{
    const VISIBILITY_PUBLIC = "public";
    const VISIBILITY_PROTECTED = "protected";
    const VISIBILITY_PRIVATE = "private";

    private $visibility = self::VISIBILITY_PRIVATE;

    private $static = false;

    private $name;

    private $initialValue;

    public function __construct(
        $name,
        $initialValue = null,
        $visibility = self::VISIBILITY_PRIVATE,
        $static = false
    )
    {
        $this->name = (string) $name;
        $this->initialValue = $initialValue;
        $this->visibility = $visibility;
        $this->static = (bool) $static;
    }

    /**
     * @return mixed
     */
    public function getInitialValue()
    {
        return $this->initialValue;
    }

    /**
     * @param mixed $initialValue
     */
    public function setInitialValue($initialValue)
    {
        $this->initialValue = $initialValue;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * @param boolean $static
     */
    public function setStatic($static)
    {
        $this->static = (bool) $static;
    }

    public function generate()
    {
        $code = $this->getVisibility();

        if ($this->isStatic()) {
            $code .= " static";
        }

        $code .= " \${$this->getName()}";

        if (null !== $this->getInitialValue()) {
            $initialValue = var_export($this->getInitialValue(), true);
            if (false !== strpos($initialValue, '::')) {
                $initialValue = $this->getInitialValue();
            }

            $code .= " = " . $initialValue;
        }

        return $code . ";\n";
    }
}