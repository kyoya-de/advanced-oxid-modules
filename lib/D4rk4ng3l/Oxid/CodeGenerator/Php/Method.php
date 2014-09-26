<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 10:03
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Method\Parameter;

/**
 * Class Method
 *
 * @package D4rk4ng3l\Oxid\CodeGenerator\Php
 */
class Method implements GeneratorInterface
{
    /**
     *
     */
    const VISIBILITY_PUBLIC = "public";
    /**
     *
     */
    const VISIBILITY_PROTECTED = "protected";
    /**
     *
     */
    const VISIBILITY_PRIVATE = "private";

    /**
     * @var string
     */
    private $visibility = self::VISIBILITY_PUBLIC;

    /**
     * @var bool
     */
    private $static = false;

    /**
     * @var bool
     */
    private $abstract = false;

    /**
     * @var bool
     */
    private $final = false;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $body;

    /**
     * @var Parameter[]
     */
    private $parameters = array();

    /**
     * @param string $name
     * @param array  $parameters
     * @param string $body
     * @param string $visibility
     * @param bool   $static
     * @param bool   $abstract
     * @param bool   $final
     */
    public function __construct(
        $name,
        $parameters = array(),
        $body = "",
        $visibility = self::VISIBILITY_PUBLIC,
        $static = false,
        $abstract = false,
        $final = false
    )
    {
        $this->name = (string) $name;
        $this->parameters = (array) $parameters;
        $this->body = (string) $body;
        $this->visibility = $visibility;
        $this->static = (bool) $static;
        $this->abstract = (bool) $abstract;
        $this->final = (bool) $final;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param boolean $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return boolean
     */
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * @param boolean $final
     */
    public function setFinal($final)
    {
        $this->final = $final;
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
     * @return Parameter[]
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
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
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
        $this->static = $static;
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
    public function generate()
    {
        $code = "";
        if ($this->isAbstract() && $this->isFinal()) {
            throw new \InvalidArgumentException("A method can not be 'abstract' and 'final' together!");
        }

        if ($this->isFinal()) {
            $code .= "final ";
        }

        if ($this->isAbstract()) {
            $code .= "abstract ";
        }

        $code .= $this->getVisibility();

        if ($this->isStatic()) {
            $code .= " static";
        }

        $code .= " function {$this->getName()}(";
        $parameters = array();
        foreach ($this->getParameters() as $parameter) {

            $parameters[] = $parameter->generate();
        }

        $code .= implode(", ", $parameters) . ")";

        if ($this->isAbstract()) {
            $code .= ";\n";
            return $code;
        }

        $code .= "\n{\n";
        $code .= $this->getBody();
        $code .= "\n}\n";
        
        return $code;
    }
}