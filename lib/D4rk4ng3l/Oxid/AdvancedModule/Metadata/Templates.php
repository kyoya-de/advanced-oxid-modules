<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:19
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class Templates
 *
 * @package D4rk4ng3l\Oxid\AdvancedModule\Metadata
 */
class Templates implements GeneratorInterface
{

    /**
     * @var array
     */
    private $templates = array();

    /**
     * @param $name
     * @param $file
     *
     * @return $this
     */
    public function addTemplate($name, $file)
    {
        $this->templates[$name] = $file;

        return $this;
    }

    /**
     * @return array
     */
    public function generate()
    {
        return $this->templates;
    }
}