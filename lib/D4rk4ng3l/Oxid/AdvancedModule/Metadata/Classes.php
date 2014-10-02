<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:18
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class Classes
 *
 * @package D4rk4ng3l\Oxid\AdvancedModule\Metadata
 */
class Classes implements GeneratorInterface
{

    /**
     * @var array
     */
    private $classes = array();

    /**
     * @param $class
     * @param $file
     *
     * @return Classes
     */
    public function addClass($class, $file)
    {
        $this->classes[$class] = $file;

        return $this;
    }

    /**
     * @return array
     */
    public function generate()
    {
        return $this->classes;
    }
}