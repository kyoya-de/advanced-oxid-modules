<?php

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Constant implements GeneratorInterface
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function generate()
    {
        return "const {$this->getName()} = " . var_export($this->getValue(), true) . ";\n";
    }
}