<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

class DropdownAttribute extends Attribute implements iAttributeInterface
{
    /**
     * All the values of the current attribute
     *
     * @var mixed
     *
     * @access protected
     */
    protected $values;

    /**
     * __construct
     * 
     * @param string $name   Description.
     * @param array  $values Description.
     * @param array  $flags  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct($name = '', $values = [], $labels = [], $flags = [])
    {
        $this->name  = $name;
        $this->flags = $flags;

        if (count($labels) === 0) {
            $labels = $values;
        }

        $this->values = array_combine($values, $labels);
    }

    /**
     * Returns the label that belongs to the value
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string Value.
     */
    public function getDisplayValue(Model $model)
    {
        $value = $model->{$this->name};
        if (array_key_exists($value, $this->values)) {
            return $this->values[$value];
        }

        return 'Geen';
    }
}
