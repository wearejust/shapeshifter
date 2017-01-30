<?php

namespace Just\Shapeshifter\Attributes;

abstract class Attribute
{
    /**
     * The name of the attribute
     *
     * @var string
     */
    protected $name;

    /**
     * Flags for the attribute
     *
     * @var array
     */
    protected $flags;

    /**
     * @return string
     */
    abstract public function getHandler();

    /**
     * @param string $name  Description.
     * @param array  $flags Description.
     */
    public function __construct($name, array $flags = [])
    {
        $this->name  = $name;
        $this->flags = $flags;
    }


    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag)
    {
        return in_array($flag, $this->flags);
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'flags' => $this->getFlags()
        ];
    }
}
