<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:18
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Extensions implements GeneratorInterface
{

    /**
     * @var array
     */
    private $extensions = array();

    /**
     * @param $oxidClass
     * @param $moduleClass
     *
     * @return $this
     */
    public function addExtension($oxidClass, $moduleClass)
    {
        $this->extensions[$oxidClass] = $moduleClass;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     *
     * @return Extensions
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @return array
     */
    public function generate()
    {
        return $this->extensions;
    }
}