<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 11:12
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class PhpInclude implements GeneratorInterface
{
    const TYPE_INCLUDE = "include";
    const TYPE_INCLUDE_ONCE = "include_once";
    const TYPE_REQUIRE = "require";
    const TYPE_REQUIRE_ONCE = "require_once";

    private $includeType = self::TYPE_INCLUDE;

    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     * @param string $includeType
     */
    public function __construct($file, $includeType = self::TYPE_INCLUDE)
    {
        $this->file = (string) $file;
        $this->includeType = (string) $includeType;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getIncludeType()
    {
        return $this->includeType;
    }

    /**
     * @param string $includeType
     */
    public function setIncludeType($includeType)
    {
        $this->includeType = $includeType;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = $this->includeType . " " . var_export($this->file, true) . ";\n";

        return $code;
    }
}