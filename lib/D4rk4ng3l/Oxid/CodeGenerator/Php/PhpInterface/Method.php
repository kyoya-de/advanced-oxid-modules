<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 11:25
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php\PhpInterface;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Method\Parameter;

class Method implements GeneratorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Parameter[]
     */
    private $parameters = array();

    public function __construct($name, $parameters = array())
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param Parameter $parameter
     *
     * @return $this
     */
    public function addParameter(Parameter $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @param Parameter[] $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = "public function {$this->name}(";
        $params = array();
        foreach ($this->parameters as $parameter) {
            $params[] = $parameter->generate();
        }

        $code .= implode(", ", $params) . ");\n";

        return $code;
    }
}