<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:19
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;

use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

/**
 * Class Events
 *
 * @package D4rk4ng3l\Oxid\AdvancedModule\Metadata
 */
class Events implements GeneratorInterface
{
    const EVENT_ACTIVATE = "onActivate";
    const EVENT_DEACTIVATE = "onDeactivate";

    /**
     * @var string
     */
    private $events = array();

    /**
     * @param string $event
     *
     * @return string
     */
    public function getEvent($event)
    {
        if (isset($this->events[$event])) {
            return $this->events[$event];
        }

        return "";
    }

    /**
     * @param string $event
     * @param string $command
     *
     * @return Events
     */
    public function setEvent($event, $command)
    {
        $this->events[$event] = (string) $command;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function generate()
    {
        return $this->events;
    }
}