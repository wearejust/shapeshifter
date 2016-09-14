<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

class CheckboxAttribute extends Attribute implements iAttributeInterface
{
    /**
     * @param string $value
     * @param int    $oldValue
     *
     * @return bool
     */
    public function setAttributeValue($value, $oldValue = 0)
    {
        $this->value = $value ?: 0;
    }

    /**
     * getDisplayValue
     *
     * @param Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        return $model->{$this->name} ? translateAttribute('yes') : translateAttribute('no');
    }

    /**
     * getSaveValue
     *
     * @access public
     *
     * @param Model $model
     *
     * @return mixed Value.
     */
    public function getSaveValue(Model $model)
    {
        $model->{$this->name} = $this->value ?: 0;
    }
}
