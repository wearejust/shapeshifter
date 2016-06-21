<?php

namespace Just\Shapeshifter\Form\Elements;

use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;

class Tab
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
     * @var Collection|Section[]
     */
    protected $sections;

    /**
     * Tab constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name       = $name;
        $this->attributes = new Collection();
        $this->sections   = new Collection();
    }

    /**
     * @param Attribute $attribute
     */
    public function add(Attribute $attribute)
    {
        $this->attributes->push($attribute);
    }

    /**
     * @param $name
     * @param $callback
     */
    public function section($name, $callback)
    {
        $section = new Section($name);

        call_user_func($callback, $section);

        $this->sections->push($section);
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return str_slug($this->name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}
