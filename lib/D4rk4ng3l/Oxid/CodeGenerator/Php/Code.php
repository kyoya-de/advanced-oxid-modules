<?php
namespace D4rk4ng3l\Oxid\CodeGenerator\Php;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class Code
 *
 * @package D4rk4ng3l\Oxid\CodeGenerator\Php
 */
class Code implements GeneratorInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct($code = "")
    {
        $this->code = (string) $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = (string) $code;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->code;
    }
}