<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 11:02
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class PhpNamespace implements GeneratorInterface
{

    /**
     * @var string
     */
    private $name;

    public function __construct($name = null)
    {
        $this->name = (string) $name;
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
     * @return string
     */
    public function generate()
    {
        $code = "namespace {$this->name};\n";

        return $code;

    }
}