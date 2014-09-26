<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 10:33
 */

namespace D4rk4ng3l\Oxid\CodeGenerator\Php;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class PhpFile implements GeneratorInterface
{
    /**
     * @var GeneratorInterface[]
     */
    private $blocks = array();

    /**
     * @param GeneratorInterface $block
     */
    public function addBlock(GeneratorInterface $block)
    {
        $this->blocks[] = $block;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = "<?php\n// THIS FILE WAS AUTOMATICALLY GENERATED. PLEASE DO NOT EDIT.\n";

        foreach ($this->blocks as $block) {
            $code .= $block->generate();
        }

        return $code;
    }
}