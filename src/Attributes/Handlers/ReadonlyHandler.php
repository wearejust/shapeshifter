<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\View\AttributeView;

class ReadonlyHandler extends Handler
{
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
        return $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return mixed
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView('Readonly', [
            'name' => $attribute->getName(),
            'flags' => $attribute->getFlags(),
            'model' => $model,
        ]);
    }
}
