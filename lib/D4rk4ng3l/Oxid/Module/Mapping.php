<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.09.14
 * Time: 04:54
 */

namespace D4rk4ng3l\Oxid\Module;


class Mapping
{
    private $oxidClass;

    private $oxidMethod;

    private $moduleFile;

    private $moduleClass;

    private $moduleMethod;

    private $parentExecution;

    private $returns;

    private $hasReturn;

    /**
     * @param mixed $hasReturn
     *
     * @return Mapping
     */
    public function setReturn($hasReturn)
    {
        $this->hasReturn = $hasReturn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function hasReturn()
    {
        return $this->hasReturn;
    }

    /**
     * @param mixed $moduleClass
     *
     * @return Mapping
     */
    public function setModuleClass($moduleClass)
    {
        $this->moduleClass = $moduleClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModuleClass()
    {
        return $this->moduleClass;
    }

    /**
     * @param mixed $moduleMethod
     *
     * @return Mapping
     */
    public function setModuleMethod($moduleMethod)
    {
        $this->moduleMethod = $moduleMethod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModuleMethod()
    {
        return $this->moduleMethod;
    }

    /**
     * @param mixed $oxidClass
     *
     * @return Mapping
     */
    public function setOxidClass($oxidClass)
    {
        $this->oxidClass = $oxidClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOxidClass()
    {
        return $this->oxidClass;
    }

    /**
     * @param mixed $oxidMethod
     *
     * @return Mapping
     */
    public function setOxidMethod($oxidMethod)
    {
        $this->oxidMethod = $oxidMethod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOxidMethod()
    {
        return $this->oxidMethod;
    }

    /**
     * @param mixed $parentExecution
     *
     * @return Mapping
     */
    public function setParentExecution($parentExecution)
    {
        $this->parentExecution = $parentExecution;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentExecution()
    {
        return $this->parentExecution;
    }

    /**
     * @param mixed $returns
     *
     * @return Mapping
     */
    public function setReturns($returns)
    {
        $this->returns = $returns;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturns()
    {
        return $this->returns;
    }

    /**
     * @param mixed $moduleFile
     */
    public function setModuleFile($moduleFile)
    {
        $this->moduleFile = $moduleFile;
    }

    /**
     * @return mixed
     */
    public function getModuleFile()
    {
        return $this->moduleFile;
    }
}