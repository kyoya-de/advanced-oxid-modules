<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 10:42
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Method\Parameter;

/**
 * Class PhpFunction
 *
 * @package D4rk4ng3l\Oxid\CodeGenerator\Php
 */
class PhpFunction implements GeneratorInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var Parameter[]
     */
    private $parameters;

    /**
     * @var string
     */
    private $body;

    /**
     * @param        $name
     * @param array  $parameters
     * @param string $body
     */
    public function __construct($name, $parameters = array(), $body = "")
    {
        $this->name = $name;
        $this->parameters = $parameters;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
     * @return Method\Parameter[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param Parameter $parameter
     */
    public function addParameter(Parameter $parameter)
    {
        $this->parameters[] = $parameter;
    }

    /**
     * @param Method\Parameter[] $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function generate()
    {
        $code = "function {$this->getName()}(";

        $parameters = array();
        foreach ($this->parameters as $parameter) {
            $parameters[] = $parameter->generate();
        }

        $code .= implode(", ", $parameters) . ")\n{";
        $code .= $this->getBody();
        $code .= "}\n";

        return $code;
    }
}