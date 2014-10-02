<?php
namespace D4rk4ng3l\Oxid\AdvancedModule\Annotations;

/**
 * @Annotation
 */
class Method
{
    const EXECUTE_NONE = "none";
    const EXECUTE_BEFORE = "before";
    const EXECUTE_AFTER = "after";

    private $class;
    private $method;
    private $parentExecution = self::EXECUTE_BEFORE;
    private $hasReturn = true;

    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['class'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" doesn\'t exist!', $key));
            }

            $this->$key = $value;
        }

        if (null === $this->class) {
            throw new \InvalidArgumentException('Missing target OXID class.');
        }

        if (null === $this->method) {
            throw new \InvalidArgumentException('Missing target method in given OXID class.');
        }

        $this->hasReturn = (bool) $this->hasReturn;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return boolean
     */
    public function hasReturnValue()
    {
        return $this->hasReturn;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getParentExecution()
    {
        return $this->parentExecution;
    }
}