<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:18
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Block implements GeneratorInterface
{

    /**
     * @var string
     */
    private $oxidTemplate = "";

    /**
     * @var string
     */
    private $name = "";

    /**
     * @var string
     */
    private $moduleTemplate = "";

    /**
     * @param string $oxidTemplate
     * @param string $name
     * @param string $moduleTemptlate
     */
    public function __construct($oxidTemplate, $name, $moduleTemptlate)
    {
        $this->name           = $name;
        $this->oxidTemplate   = $oxidTemplate;
        $this->moduleTemplate = $moduleTemptlate;
    }

    /**
     * @return string
     */
    public function getModuleTemplate()
    {
        return $this->moduleTemplate;
    }

    /**
     * @param string $moduleTemplate
     */
    public function setModuleTemplate($moduleTemplate)
    {
        $this->moduleTemplate = $moduleTemplate;
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
    public function getOxidTemplate()
    {
        return $this->oxidTemplate;
    }

    /**
     * @param string $oxidTemplate
     */
    public function setOxidTemplate($oxidTemplate)
    {
        $this->oxidTemplate = $oxidTemplate;
    }

    /**
     * @return array
     */
    public function generate()
    {
        return array(
            'template' => $this->oxidTemplate,
            'block'    => $this->name,
            'file'     => $this->moduleTemplate,
        );
    }
}