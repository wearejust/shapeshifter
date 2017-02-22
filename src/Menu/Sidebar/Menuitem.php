<?php

namespace Just\Shapeshifter\Menu\Sidebar;

use Illuminate\Contracts\Support\Arrayable;

class Menuitem implements Arrayable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $module;

    /**
     * @param string $name
     * @param string $module
     */
    public function __construct($name, $module)
    {
        $this->name = $name;
        $this->module = $module;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'name' => $this->name,
            'module' => $this->getModule(),
            'actions' => [
                'index' => route($this->getModule() . '.index')
            ]
        ];
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getModule() : string
    {
        return $this->module;
    }

    function __toString()
    {
        return json_encode($this->toArray());
    }
}
