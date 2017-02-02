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
    public function toArray()
    {
        return [
            'name' => $this->name,
            'module' => $this->module,
            'actions' => [
                'index' => route($this->module . '.index')
            ]
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    function __toString()
    {
        return json_encode($this->toArray());
    }
}
