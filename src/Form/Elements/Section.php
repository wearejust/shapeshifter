<?php

namespace Just\Shapeshifter\Form\Elements;

use Illuminate\Support\Collection;

class Section
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->attributes = new Collection();
        $this->name       = $name;
    }

    /**
     * @param $attribute
     */
    public function add($attribute)
    {
        $this->attributes->push($attribute);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
