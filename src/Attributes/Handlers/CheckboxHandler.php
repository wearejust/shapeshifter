<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\View\AttributeView;

class CheckboxHandler extends Handler
{
    /**
     * @param Model                                   $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model, Attribute $attribute)
    {
        return (bool) $model->{$attribute->getName()};
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param                                         $value
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setModelValue(Model $model, $value, Attribute $attribute)
    {
        $model->{$attribute->getName()} = (bool) $value;

        return $model;
    }

    /**
     *
     */
    protected function getViewName()
    {
        return 'Checkbox';
    }
}
