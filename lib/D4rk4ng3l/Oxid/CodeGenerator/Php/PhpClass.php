<?php
namespace D4rk4ng3l\Oxid\CodeGenerator\Php;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class PhpClass
 *
 * @package D4rk4ng3l\Oxid\CodeGenerator\Php
 */
class PhpClass implements GeneratorInterface
{

    /**
     * @var bool
     */
    private $final = false;

    /**
     * @var bool
     */
    private $abstract = false;

    /**
     * @var string
     */
    private $namespace = "";

    /**
     * @var array
     */
    private $phpUse = array();

    /**
     * @var string
     */
    private $name = "";

    /**
     * @var string
     */
    private $extension = "";

    /**
     * @var array
     */
    private $implementations = array();

    /**
     * @var Constant[]
     */
    private $constants = array();

    /**
     * @var Property[]
     */
    private $properties = array();

    /**
     * @var Method[]
     */
    private $methods = array();

    /**
     * @param string $name
     * @param PhpUse $phpUse
     */
    public function __construct($name, PhpUse $phpUse = null)
    {
        $this->name = (string) $name;
        if (null === $phpUse) {
            $phpUse = new PhpUse();
        }

        $this->phpUse = $phpUse;
    }

    /**
     * @param $className
     *
     * @return $this
     */
    public function addImplementation($className)
    {
        $this->implementations[$className] = $this->phpUse->getClassAlias($className);

        return $this;
    }

    /**
     * @param $extension
     *
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $this->phpUse->getClassAlias($extension);

        return $this;
    }

    /**
     * @param Constant $constant
     *
     * @return $this
     */
    public function addConstant(Constant $constant)
    {
        $this->constants[] = $constant;

        return $this;
    }

    /**
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @param Method $method
     *
     * @return $this
     */
    public function addMethod(Method $method)
    {
        $this->methods[$method->getName()] = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function generate()
    {
        if ($this->final && $this->abstract) {
            throw new \InvalidArgumentException("A class can not be 'abstract' and 'final' together!");
        }

        $code = "class {$this->name}";

        if (0 < strlen($this->extension)) {
            $code .= " extends {$this->extension}";
        }

        if (0 < count($this->implementations)) {
            $code .= " implements " . implode($this->implementations);
        }

        $code .= "\n{\n";

        foreach ($this->constants as $constant) {
            $code .= $constant->generate();
        }

        foreach ($this->properties as $property) {
            $code .= $property->generate();
        }

        foreach ($this->methods as $method) {
            $code .= $method->generate();
        }

        $code .= "\n}\n";

        return $code;
    }
}