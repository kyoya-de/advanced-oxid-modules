<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 26.09.2014
 * Time: 12:18
 */

namespace D4rk4ng3l\Oxid\AdvancedModule\Metadata;


use D4rk4ng3l\Oxid\CodeGenerator\GeneratorInterface;

class Setting implements GeneratorInterface
{
    const TYPE_STRING = 'str';
    const TYPE_BOOL = 'bool';
    const TYPE_BOOLEAN = self::TYPE_BOOL;
    const TYPE_ARRAY = 'arr';
    const TYPE_ARRAY_ASSOC = 'aarr';
    const TYPE_SELECT = 'select';

    /**
     * @var string
     */
    private $group = 'main';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $type = self::TYPE_STRING;

    /**
     * @var mixed
     */
    private $value = '';

    /**
     * @var array
     */
    private $selectValues = array();

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param string $group
     * @param string $name
     * @param string $type
     * @param mixed $value
     */
    public function __construct($group, $name, $type, $value)
    {
        $this->group = $group;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return array
     */
    public function getSelectValues()
    {
        return $this->selectValues;
    }

    /**
     * @param $value
     */
    public function addSelectValue($value)
    {
        $this->selectValues[] = $value;
    }

    /**
     * @param array $selectValues
     */
    public function setSelectValues($selectValues)
    {
        $this->selectValues = $selectValues;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function generate()
    {
        $metaData = array(
            'group' => $this->group,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
        );

        if (self::TYPE_SELECT == $this->type) {
            $metaData['constraints'] = implode("|", $this->selectValues);
            $metaData['position'] = $this->position;
        }

        return $metaData;
    }
}