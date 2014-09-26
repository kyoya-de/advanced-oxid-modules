<?php

namespace D4rk4ng3l\Oxid\AdvancedModule;

abstract class Metatdata
{
    public function getMDVersion()
    {
        return "1.1";
    }

    public function getTitle()
    {
        return "Advanced OXID Module";
    }

    public function getDescription()
    {
        return array(
            'de' => 'Dies ist ein erweitertes OXID-Modul',
            'en' => 'This is an advanced OXID module',
        );
    }

    public function getThumbnail()
    {
        return "";
    }

    public function getVersion()
    {
        return "1.0";
    }

    public function getAuthor()
    {
        return "Adv. OXID module generator";
    }

    public function getURL()
    {
        return "https://github.com/D4rk4ng3l/advanced"
    }

    abstract public function getId();
} 