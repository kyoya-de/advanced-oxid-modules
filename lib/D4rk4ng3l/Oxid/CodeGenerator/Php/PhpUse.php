<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 11:04
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class PhpUse implements GeneratorInterface
{
    private $use = array();

    /**
     * @param $className
     */
    public function addUse($className)
    {
        $this->use[$className] = "usage" . count($this->use);
    }

    /**
     * @param $className
     *
     * @return mixed
     */
    public function getClassAlias($className)
    {
        $this->addUse($className);

        return $this->use[$className];
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = "";

        foreach ($this->use as $use => $alias) {
            $code .= "$use as $alias\n";
        }

        return $code;
    }
}