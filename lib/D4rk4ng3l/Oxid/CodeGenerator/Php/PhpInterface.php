<?php

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;
use D4rk4ng3l\Oxid\CodeGenerator\Php\PhpInterface\Method;

/**
 * Class PhpInterface
 *
 * @package D4rk4ng3l\Oxid\CodeGenerator\Php
 */
class PhpInterface implements GeneratorInterface
{

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $extension;

    /**
     * @var PhpUse
     */
    private $phpUse;

    /**
     * @var Method[]
     */
    private $methods = array();

    /**
     * @param string $name
     * @param string $extension
     * @param PhpUse $phpUse
     */
    public function __construct($name, $extension = "", PhpUse $phpUse = null)
    {
        $this->name = $name;

        if (null === $phpUse) {
            $phpUse = new PhpUse();
        }

        $this->phpUse = $phpUse;

        if (0 < strlen($extension)) {
            $this->setExtension($extension);
        }
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $this->phpUse->getClassAlias($extension);
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
    public function getPhpUse()
    {
        return $this->phpUse;
    }

    /**
     * @param mixed $phpUse
     */
    public function setPhpUse($phpUse)
    {
        $this->phpUse = $phpUse;
    }

    /**
     * @return Method[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->methods[] = $method;
    }

    /**
     * @param Method[] $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = "interface {$this->name}";

        if (0 < strlen($this->extension)) {
            $code .= " extends {$this->extension}";
        }

        $code .= "\n{";

        foreach ($this->methods as $method) {
            $code .= $method->generate();
        }

        $code .= "\n}\n";

        return $code;
    }
}
