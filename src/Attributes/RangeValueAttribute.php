<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * RangeValueAttribute
 */
class RangeValueAttribute extends Attribute implements iAttributeInterface
{
    /**
     * @var int
     */
    public $min;
    /**
     * @var int
     */
    public $max;
    /**
     * @var int
     */
    public $step;
    /**
     * @var string
     */
    public $label;

    /**
     * @param string $label
     * @param string $name
     * @param int $min
     * @param int $max
     * @param int $step
     * @param array $flags
     * @internal param array $value
     */
    public function __construct($label, $name, $min = 0, $max = 100, $step = 1, array $flags = [])
    {
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->label = $label;
        
        parent::__construct($name, $flags);
    }

    /**
     * This function is fired when an record is saved. It means each attribute can
     * have it's own function to specifiy what is saved in the Database.
     *
     * @param Model $model
     * @return Model|void
     */
    public function getSaveValue(Model $model)
    {
        return $model;
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function getLabel($name)
    {
        $label = $this->label;

        if ($this->required) {
            $label .= ' *';
        }

        return $label;
    }

}
