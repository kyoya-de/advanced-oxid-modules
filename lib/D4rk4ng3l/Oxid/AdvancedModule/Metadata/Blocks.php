<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:45
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Blocks implements GeneratorInterface
{

    /**
     * @var Block[]
     */
    private $blocks = array();

    /**
     * @param Block $block
     *
     * @return Blocks
     */
    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * @return array
     */
    public function generate()
    {
        $metadata = array();

        foreach ($this->blocks as $block) {
            $metadata[] = $block->generate();
        }

        return $metadata;
    }
}