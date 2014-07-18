<?php namespace Just\Shapeshifter\Form\Elements;

use Illuminate\Support\Collection;

class Section
{
    protected $name;
    protected $attributes;

    public function __construct($name)
    {
        $this->attributes = new Collection();
        $this->name = $name;
    }

    public function add($attribute)
    {
        $this->attributes->push($attribute);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
} 
