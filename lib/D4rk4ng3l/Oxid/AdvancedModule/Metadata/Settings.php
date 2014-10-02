<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:43
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class Settings
 *
 * @package D4rk4ng3l\Oxid\AdvancedModule\Metadata
 */
class Settings implements GeneratorInterface
{

    /**
     * @var Setting[]
     */
    private $settings = array();

    /**
     * @param Setting $setting
     *
     * @return Settings
     */
    public function addSetting(Setting $setting)
    {
        $this->settings[] = $setting;

        return $this;
    }

    /**
     * @return array
     */
    public function generate()
    {
        $metadata = array();

        foreach ($this->settings as $setting) {
            $metadata[] = $setting->generate();
        }

        return $metadata;
    }
}