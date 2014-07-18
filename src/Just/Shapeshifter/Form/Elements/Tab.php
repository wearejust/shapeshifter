<?php namespace Just\Shapeshifter\Form\Elements;

use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;

class Tab
{
    protected $name;
    protected $attributes;
    protected $sections;

    public function __construct($name)
    {
        $this->name = $name;
        $this->attributes = new Collection();
        $this->sections = new Collection();
    }

    public function add(Attribute $attribute)
    {
        $this->attributes->push($attribute);
    }

    public function section($name, $callback)
    {
        $section = new Section($name);

        call_user_func($callback, $section);

        $this->sections->push($section);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getSlug()
    {
        return \Str::slug($this->name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

} 
