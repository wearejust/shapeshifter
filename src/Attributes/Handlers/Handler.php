<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;

abstract class Handler
{
    /**
     */
    abstract protected function getViewName();

    /**
     * Base display function to display the view of the attribute.
     * Each attribute has it's own view, with the name (ReadonlyAttribute.blade.php)
     * as an name
     *
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function compile(Model $model, Attribute $attribute)
    {
        if ($attribute->hasFlag('readonly')) {
            $attribute = new Readonly($this->name, $this->flags);

            return $attribute->view($model, $attribute);
        }

        return $this->view($model, $attribute);
    }


    /**
     * This function is fired when in edit mode (form). This function returns
     * an string, which is placed into the form field
     *
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return string .
     */
    public function getEditValue(Model $model, Attribute $attribute)
    {
        return $model->{$attribute->getName()};
    }

    /**
     * Sets the value of the model
     *
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param                                         $value
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed Value.
     */
    public function setModelValue(Model $model, $value, Attribute $attribute)
    {
        $model->setAttribute($attribute->getName(), $value);

        return $model;
    }

    /**
     * This function is fired when in an list view. Each attribute can have it's own function
     * and the return value is the thing you can see in the list
     *
     *
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model, Attribute $attribute)
    {
        return $model->{$attribute->getName()};
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param \Just\Shapeshifter\Attributes\Attribute  $attribute
     *
     * @return AttributeView
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView($this->getViewName(), array_merge($attribute->toArray(), compact('model')));
    }
}
