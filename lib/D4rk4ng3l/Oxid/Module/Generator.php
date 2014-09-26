<?php
namespace D4rk4ng3l\Oxid\Module;


class Generator
{
    private $classesToLoad = array();
    
    private $mappings = array();

    public function addMapping(Mapping $mapping)
    {
        if (!isset($this->mappings[$mapping->getOxidClass()])) {
            $this->mappings[$mapping->getOxidClass()] = array();
        }

        if (!isset($this->mappings[$mapping->getOxidClass()][$mapping->getOxidMethod()])) {
            $this->mappings[$mapping->getOxidClass()][$mapping->getOxidMethod()] = array();
        }

        $this->mappings[$mapping->getOxidClass()][$mapping->getOxidMethod()][] = $mapping;
        if (!isset($this->classesToLoad[$mapping->getModuleClass()])) {
            $this->classesToLoad[$mapping->getModuleClass()] = $mapping->getModuleFile();
        }

        return $this;
    }

    public function generate()
    {

    }
} 