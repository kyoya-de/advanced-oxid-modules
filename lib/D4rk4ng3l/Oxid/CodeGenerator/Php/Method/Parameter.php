<?php

namespace D4rk4ng3l\Oxid\CodeGenerator\Php\Method;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Parameter implements GeneratorInterface
{
    private $name;

    private $initialValue;

    private $required = true;


    public function __construct($name, $initialValue = null, $required = true)
    {
        $this->name = $name;
        $this->initialValue = $initialValue;
        $this->required = $required;
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
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = (bool) $required;
    }

    public function generate()
    {
        $param = "\$" . $this->getName();
        if (!$this->isRequired()) {
            $initValue = var_export($this->getInitialValue(), true);
            if (false !== strpos($initValue, '::')) {
                $initValue = $this->getInitialValue();
            }
            $param .= " = " . $initValue;
        }

        return $param;
    }
}