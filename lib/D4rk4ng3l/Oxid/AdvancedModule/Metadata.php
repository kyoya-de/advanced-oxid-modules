<?php

namespace D4rk4ng3l\Oxid\AdvancedModule;

use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Blocks;
use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Classes;
use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Events;
use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Extensions;
use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Settings;
use D4rk4ng3l\Oxid\AdvancedModule\Metadata\Templates;
use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

abstract class Metadata implements GeneratorInterface
{

    /**
     * @var string
     */
    private $metadataVersion = "1.1";

    /**
     * @var string
     */
    private $id = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $thumbnail = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $author = '';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var Extensions
     */
    private $extensions;

    /**
     * @var Classes
     */
    private $files;

    /**
     * @var Events
     */
    private $events;

    /**
     * @var Blocks
     */
    private $blocks;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var Templates
     */
    private $templates;

    public function __construct($id = null, $skipConfig = false)
    {
        $this->title = $this->getTitle();

        if (null === $id) {
            $this->id = $this->getId();
        } else {
            $this->id = $id;
            $this->title .= " ({$id})";
        }

        $this->init();

        $this->configure();

        if ($skipConfig) {
            $this->init();
        }
    }

    /**
     *
     */
    protected function configure()
    {
    }

    /**
     * @return string
     */
    abstract public function getId();

    /**
     * @return string
     */
    abstract public function getTitle();

    /**
     * @param $description
     *
     * @return Metadata
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $author
     *
     * @return Metadata
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @param mixed $url
     *
     * @return Metadata
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param mixed $version
     *
     * @return Metadata
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param mixed $email
     *
     * @return Metadata
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param mixed $thumbnail
     *
     * @return Metadata
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @param string $metadataVersion
     *
     * @return Metadata
     */
    public function setMetadataVersion($metadataVersion)
    {
        $this->metadataVersion = $metadataVersion;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private function export($value)
    {
        return var_export($value, true);
    }

    /**
     * @return Blocks
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @return Events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return Extensions
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @return Classes
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return Settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return Templates
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $code = "<?php\n";
        $code .= "\$sMetadataVersion = " . $this->export($this->metadataVersion) . ";\n";
        $code .= "\$aModule = ";

        $code .= $this->export(
            array(
                'id'          => $this->id,
                'title'       => $this->title,
                'description' => $this->description,
                'thumbnail'   => $this->thumbnail,
                'version'     => $this->version,
                'author'      => $this->author,
                'url'         => $this->url,
                'email'       => $this->email,
                'extend'      => $this->extensions->generate(),
                'files'       => $this->files->generate(),
                'events'      => $this->events->generate(),
                'blocks'      => $this->blocks->generate(),
                'templates'   => $this->templates->generate(),
                'settings'    => $this->settings->generate(),
            )
        );

        $code .= ";\n";

        return $code;
    }

    private function init()
    {
        $this->extensions = new Extensions();
        $this->files      = new Classes();
        $this->events     = new Events();
        $this->blocks     = new Blocks();
        $this->settings   = new Settings();
        $this->templates  = new Templates();
    }
}